<?php

namespace App\Filament\Resources\Blog\Posts\Pages;

use App\Filament\DemoIconAlias;
use App\Filament\Resources\Blog\Posts\PostResource;
use App\Models\Blog\Post;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    public function getTitle(): string | Htmlable
    {
        /** @var Post */
        $record = $this->getRecord();

        return $record->title;
    }

    protected function getActions(): array
    {
        return [
            Action::make('quick_publish')
                ->icon(FilamentIcon::resolve(DemoIconAlias::RESOURCES_BLOG_POSTS_ACTIONS_PUBLISH) ?? Heroicon::RocketLaunch)
                ->color('success')
                ->keyBindings(['mod+shift+p'])
                ->visible(fn (Post $record): bool => ! $record->published_at?->isPast())
                ->action(function (Post $record): void {
                    $record->update(['published_at' => now()]);
                    $this->refreshFormData(['published_at']);

                    Notification::make()
                        ->title('Post published')
                        ->success()
                        ->send();
                }),
            Action::make('unpublish')
                ->icon(FilamentIcon::resolve(DemoIconAlias::RESOURCES_BLOG_POSTS_ACTIONS_UNPUBLISH) ?? Heroicon::XCircle)
                ->color('warning')
                ->visible(fn (Post $record): bool => (bool) $record->published_at?->isPast())
                ->action(function (Post $record): void {
                    $record->update(['published_at' => null]);
                    $this->refreshFormData(['published_at']);

                    Notification::make()
                        ->title('Post unpublished')
                        ->warning()
                        ->send();
                }),
            EditAction::make(),
        ];
    }
}
