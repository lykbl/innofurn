<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lunar\Facades\ModelManifest;
use Lunar\Models\Currency;
use Lunar\Models\Price;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->overrideModels();
    }

    private function overrideModels(): void
    {
        $models = collect([
            Product::class        => \App\Models\Product::class,
            ProductVariant::class => \App\Models\ProductVariant::class,
            Price::class          => \App\Models\Price::class,
            Currency::class       => \App\Models\Currency::class,
        ]);

        ModelManifest::register($models);
    }
}
