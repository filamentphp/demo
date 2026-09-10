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
            ->getSearchResultsUsing(fn (string $search): array => User::query()->where('name', 'like', '%' . $search . '%')->limit(10)->pluck('name', 'id')->all())
            ->getLabelsUsing(fn (array $ids): array => User::query()->whereKey($ids)->pluck('name', 'id')->all());
    }

    public function contentHtml(): string
    {
        return $this->body_format === 'html'
            ? RichContentRenderer::make($this->body)->mentions([static::mentionProvider()])->toHtml()
            : nl2br(e($this->body ?? ''));
    }

    public function mentionsUser(int $userId): bool
    {
        $mentioned = false;
        if ($this->body_format === 'html') {
            RichContentRenderer::make($this->body)->getEditor()->descendants(function (stdClass $node) use ($userId, &$mentioned): void {
                if (($node->type === 'mention') && (($node->attrs->char ?? '@') === '@') && ((string) ($node->attrs->id ?? '') === (string) $userId)) {
                    $mentioned = true;
                }
            });
        }

        return $mentioned;
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
        $query->whereNotNull('body')->where('user_id', '!=', $userId)
            ->whereDoesntHave('reads', fn (Builder $reads) => $reads->where('user_id', $userId));
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<PageMessage, $this> */
    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
