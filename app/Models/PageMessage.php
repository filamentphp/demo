<?php

namespace App\Models;

use App\Models\HR\ProjectActivity;
use Filament\Forms\Components\RichEditor\MentionProvider;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use stdClass;

class PageMessage extends Model
{
    public static function mentionProvider(): MentionProvider
    {
        return MentionProvider::make('@')
            ->items(fn (): array => ['agent' => 'Agent'] + User::query()->orderBy('name')->limit(10)->get(['id', 'name'])->mapWithKeys(fn (User $user): array => ['user:' . $user->id => $user->name])->all())
            ->getSearchResultsUsing(function (string $search): array {
                $results = User::query()->where('name', 'like', '%' . $search . '%')->orderBy('name')->limit(10)->get(['id', 'name'])->mapWithKeys(fn (User $user): array => ['user:' . $user->id => $user->name])->all();

                if (str_contains(strtolower('Agent'), strtolower($search))) {
                    return ['agent' => 'Agent'] + $results;
                }

                return $results;
            })
            ->getLabelsUsing(function (array $ids): array {
                $userIds = collect($ids)->reject(fn (mixed $id): bool => $id === 'agent')->mapWithKeys(fn (mixed $id): array => [$id => str((string) $id)->after('user:')->toString()]);
                $users = User::query()->whereKey($userIds->values()->all())->pluck('name', 'id');
                $labels = $userIds->filter(fn (string $id): bool => $users->has($id))->map(fn (string $id): string => $users[$id])->all();

                return in_array('agent', $ids, true) ? ['agent' => 'Agent'] + $labels : $labels;
            });
    }

    public function contentHtml(): string
    {
        if ($this->is_agent) {
            return str($this->body ?? '')->markdown(['html_input' => 'strip', 'allow_unsafe_links' => false])->toString();
        }

        return $this->body_format === 'html'
            ? RichContentRenderer::make($this->body)->mentions([static::mentionProvider()])->toHtml()
            : nl2br(e($this->body ?? ''));
    }

    public function mentionsUser(int $userId): bool
    {
        return $this->mentions('user:' . $userId) || $this->mentions((string) $userId);
    }

    public function mentionsAgent(): bool
    {
        return $this->mentions('agent');
    }

    public function shouldReceiveAgentReply(): bool
    {
        if ($this->is_agent || $this->user_id === null || $this->body === null) {
            return false;
        }

        return $this->mentionsAgent() || ($this->parent_id !== null && static::query()
            ->whereKey($this->parent_id)->where('room', $this->room)->where('agent_paused', false)->exists() && static::query()
            ->where('room', $this->room)
            ->where('parent_id', $this->parent_id)
            ->where('is_agent', true)
            ->where('id', '<', $this->id)
            ->exists());
    }

    public function authorName(): string
    {
        return $this->is_agent ? 'Agent' : ($this->user->name ?? 'Former user');
    }

    protected function mentions(string $id): bool
    {
        $mentioned = false;

        if ($this->body_format === 'html') {
            RichContentRenderer::make($this->body)->getEditor()->descendants(function (stdClass $node) use ($id, &$mentioned): void {
                if (($node->type === 'mention') && (($node->attrs->char ?? '@') === '@') && ((string) ($node->attrs->id ?? '') === $id)) {
                    $mentioned = true;
                }
            });
        }

        return $mentioned;
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_agent' => 'boolean',
            'agent_paused' => 'boolean',
        ];
    }

    /** @return BelongsTo<ProjectActivity, $this> */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(ProjectActivity::class);
    }

    /** @return HasMany<PageMessageRead, $this> */
    public function reads(): HasMany
    {
        return $this->hasMany(PageMessageRead::class);
    }

    /** @param Builder<PageMessage> $query */
    public function scopeUnreadFor(Builder $query, int $userId): void
    {
        $query->whereNotNull('body')->where(fn (Builder $query) => $query->whereNull('user_id')->orWhere('user_id', '!=', $userId))
            ->where(fn (Builder $query) => $query->whereNull('agent_status')->orWhereIn('agent_status', ['completed', 'failed']))
            ->whereDoesntHave('reads', fn (Builder $reads) => $reads->where('user_id', $userId));
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<PageMessage, $this> */
    public function agentRequest(): BelongsTo
    {
        return $this->belongsTo(self::class, 'agent_request_id');
    }

    /** @return HasMany<PageMessage, $this> */
    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
