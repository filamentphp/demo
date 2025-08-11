<?php

namespace App\Models\Blog;

use Database\Factories\Blog\LinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Link extends Model implements HasMedia
{
    /** @use HasFactory<LinkFactory> */
    use HasFactory;

    use HasTranslations;
    use InteractsWithMedia;

    /** @var string[] */
    public $translatable = [
        'title',
        'description',
    ];

    protected $table = 'blog_links';

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('link-images')
            ->useDisk('link-images')
            ->acceptsMimeTypes(['image/jpeg'])
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(256)
                    ->height(144);
            });
    }
}
