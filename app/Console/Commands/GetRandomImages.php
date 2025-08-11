<?php

namespace App\Console\Commands;

use Database\Seeders\LocalImages;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GetRandomImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-random-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get random images stored locally, so that the seed process can be fast.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Use an empty string to get random images
        // We could fine-tune with search terms, examples: 'nature', 'people', 'city', 'abstract', 'food', 'sports', 'technics', 'transport', 'animals'
        // This needs some deeper research to get the best results

        $schemas = [
            ['amount' => 40, 'size' => LocalImages::SIZE_200x200, 'terms' => ['']],
            ['amount' => 40, 'size' => LocalImages::SIZE_1280x720, 'terms' => ['']],
        ];

        foreach ($schemas as $schema) {
            $this->getRandomImages($schema);
        }

        foreach ($schemas as $schema) {
            $this->removeDuplicates($schema);
        }
    }

    /**
     * @param  array{amount: int, size: string, terms: string[]}  $schema
     */
    protected function getRandomImages(array $schema): void
    {
        ['amount' => $amount, 'size' => $size, 'terms' => $terms] = $schema;

        $this->comment("Getting $amount random images of size $size, of topic: " . implode(', ', $terms));

        File::deleteDirectory(database_path('seeders/local_images/' . $size));

        $progressBar = $this->output->createProgressBar($amount);
        $progressBar->start();

        foreach (range(1, $amount) as $i) {
            $url = "https://source.unsplash.com/{$size}/?img=1," . implode(',', $terms);
            $image = file_get_contents($url);
            if ($image === false) {
                $this->error("Failed to get image from URL: $url");

                continue;
            }

            File::ensureDirectoryExists(database_path('seeders/local_images/' . $size));
            $filename = Str::uuid() . '.jpg';

            File::put(
                database_path(
                    path: "seeders/local_images/{$size}/{$filename}"
                ),
                contents: $image
            );

            $progressBar->advance();
        }

        $progressBar->finish();

        $this->newLine();
        $this->info('Done!');
    }

    /**
     * @param  array{size: string}  $schema
     */
    protected function removeDuplicates(array $schema): void
    {
        ['size' => $size] = $schema;

        $allFiles = fn () => collect(File::files(database_path('seeders/local_images/' . $size)));

        $uniqueImageSet = $allFiles()
            ->mapWithKeys(fn ($file) => [md5_file($file->getPathname()) => $file->getPathname()])
            ->values();

        $allFiles()
            ->map(fn ($file) => $file->getPathname())
            ->diff($uniqueImageSet)
            ->each(fn ($file) => File::delete($file));

        $this->info('Kept ' . $uniqueImageSet->count() . " unique files from size $size");
    }
}
