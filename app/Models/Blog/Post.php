<?php

declare(strict_types=1);

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Tags\HasTags;

final class Post extends Model
{
    use HasFactory;
    use HasTags;

    /**
     * @var string
     */
    protected $table = 'blog_posts';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'published_at',
        'seo_title',
        'seo_description',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'date',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'blog_author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'blog_category_id');
    }
}
