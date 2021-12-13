<?php

declare(strict_types=1);

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Author extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'blog_authors';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'photo',
        'bio',
        'github_handle',
        'twitter_handle',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'blog_author_id');
    }
}
