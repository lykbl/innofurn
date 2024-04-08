<?php

declare(strict_types=1);

namespace App\Providers;

use App\DebugLogger;
use App\DebugLoggerService;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Lunar\Base\ShippingModifiers;
use Lunar\Facades\ModelManifest;
use Lunar\Models\Address;
use Lunar\Models\Cart;
use Lunar\Models\Collection;
use Lunar\Models\Country;
use Lunar\Models\Currency;
use Lunar\Models\Customer;
use Lunar\Models\CustomerGroup;
use Lunar\Models\Discount;
use Lunar\Models\Order;
use Lunar\Models\OrderLine;
use Lunar\Models\Price;
use Lunar\Models\Product;
use Lunar\Models\ProductOption;
use Lunar\Models\ProductOptionValue;
use Lunar\Models\ProductVariant;
use Lunar\Models\Transaction;
use Lunar\Models\Url;
use MLL\GraphiQL\GraphiQLServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(GraphiQLServiceProvider::class);
            // TODO debug remove
            $this->app->singleton(DebugLogger::class, fn () => new DebugLoggerService());
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(ShippingModifiers $shippingModifiers): void
    {
        $this->overrideModels();
        $shippingModifiers->add(
            ShippingOptionsProvider::class
        );
    }

    private function overrideModels(): void
    {
        $models = collect([
            Product::class            => \App\Models\Product::class,
            ProductVariant::class     => \App\Models\ProductVariant::class,
            Price::class              => \App\Models\Price::class,
            Currency::class           => \App\Models\Currency::class,
            Url::class                => \App\Models\Url::class,
            Collection::class         => \App\Models\Collection::class,
            Cart::class               => \App\Models\Cart::class,
            Customer::class           => \App\Models\Customer::class,
            CustomerGroup::class      => \App\Models\CustomerGroups\CustomerGroup::class,
            Transaction::class        => \App\Models\Transaction::class,
            Order::class              => \App\Models\Order::class,
            Address::class            => \App\Models\Address::class,
            ProductOption::class      => \App\Models\ProductOption::class,
            ProductOptionValue::class => \App\Models\ProductOptionValue::class,
            Country::class            => \App\Models\Country::class,
            Discount::class           => \App\Models\Discount::class,
            OrderLine::class          => \App\Models\OrderLine::class,
        ]);

        ModelManifest::register($models);
    }
}
