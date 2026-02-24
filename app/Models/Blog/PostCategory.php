<?php

namespace App\Models\Blog;

use Database\Factories\Blog\PostCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostCategory extends Model
{
    /** @use HasFactory<PostCategoryFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'post_categories';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_visible' => 'boolean',
    ];

    /** @return HasMany<Post, $this> */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'post_category_id');
    }
}
