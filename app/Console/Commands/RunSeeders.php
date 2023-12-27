<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Symfony\Component\Finder\Finder;
use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\text;

class
RunSeeders extends Command
{
    protected $signature = 'seeders:run';

    protected $description = 'Run db seeders';

    public function handle(): void
    {
        $seeders = array_map(
            fn ($file) => str_replace('.php', '', $file->getFilename()),
            iterator_to_array((new Finder())->in(database_path('seeders'))->files(), false)
        );
        $selectedClasses = multisearch(
            label: 'Seeder to run?',
            options: fn (string $seederClassName) => Arr::where($seeders, static fn (string $seeder) =>
                str_contains($seeder, $seederClassName)
            ),
            placeholder: 'E.g. ProductVariantSeeder',
        );
        foreach ($selectedClasses as $selectedClass) {
            $this->call('db:seed', ['--class' => $selectedClass]);
        }

    }
}