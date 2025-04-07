<?php

namespace App\Models\Blog;

use Database\Factories\Blog\LinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Link extends Model
{
    /** @use HasFactory<LinkFactory> */
    use HasFactory;

    use HasTranslations;

    /** @var string[] */
    public $translatable = [
        'title',
        'description',
    ];

    protected $table = 'blog_links';
}
