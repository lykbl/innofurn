<?php

declare(strict_types=1);

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Address;
use App\Models\Chat\ChatMessage;
use App\Models\PaymentIntent;
use App\Models\Review\Review;
use App\Models\User;
use App\Policies\Address\AddressPolicy;
use App\Policies\ChatPolicy;
use App\Policies\Checkout\CheckoutPolicy;
use App\Policies\Review\ReviewPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Address::class       => AddressPolicy::class,
        Review::class        => ReviewPolicy::class,
        PaymentIntent::class => CheckoutPolicy::class,
        ChatMessage::class   => ChatPolicy::class,
        User::class          => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
    }
}
