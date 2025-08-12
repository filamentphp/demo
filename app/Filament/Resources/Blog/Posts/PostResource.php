<?php

namespace App\Filament\Resources\Blog\Posts;

use App\Filament\Resources\Blog\Posts\Pages\CreatePost;
use App\Filament\Resources\Blog\Posts\Pages\EditPost;
use App\Filament\Resources\Blog\Posts\Pages\ListPosts;
use App\Filament\Resources\Blog\Posts\Pages\ManagePostComments;
use App\Filament\Resources\Blog\Posts\Pages\ViewPost;
use App\Filament\Resources\Blog\Posts\Schemas\PostForm;
use App\Filament\Resources\Blog\Posts\Schemas\PostInfolist;
use App\Filament\Resources\Blog\Posts\Tables\PostsTable;
use App\Models\Blog\Post;
use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $slug = 'blog/posts';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string | UnitEnum | null $navigationGroup = 'Blog';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 0;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PostInfolist::configure($schema);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewPost::class,
            EditPost::class,
            ManagePostComments::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'comments' => ManagePostComments::route('/{record}/comments'),
            'edit' => EditPost::route('/{record}/edit'),
            'view' => ViewPost::route('/{record}'),
        ];
    }

    /** @return Builder<Post> */
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['author', 'category']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'author.name', 'category.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Post $record */
        $details = [];

        if ($record->author) {
            $details['Author'] = $record->author->name;
        }

        if ($record->category) {
            $details['Category'] = $record->category->name;
        }

        return $details;
    }
}
