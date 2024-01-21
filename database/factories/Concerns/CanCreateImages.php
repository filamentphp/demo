<?php

namespace Database\Factories\Concerns;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait CanCreateImages
{
    public function createImage(?string $url = null): ?string
    {
        try {
            $image = file_get_contents($url ?? DatabaseSeeder::IMAGE_URL);
        } catch (Throwable $exception) {
            return null;
        }

        $filename = Str::uuid() . '.jpg';

        Storage::disk('public')->put($filename, $image);

        return $filename;
    }
}
