<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Checkout\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// TODO remove me
readonly class TestController
{
    public function __construct(private CheckoutService $checkoutService)
    {
    }

    public function createPaymentIntent(Request $request): JsonResponse
    {
        $paymentIntent = $this->checkoutService->createPaymentIntent(...$request->all());

        return new JsonResponse(['clientSecret' => $paymentIntent->client_secret]);
    }

    public function capturePaymentIntent(Request $request): JsonResponse
    {
        $response = $this->checkoutService->captureIntent(...$request->all());

        return new JsonResponse(['ok']);
    }
}
