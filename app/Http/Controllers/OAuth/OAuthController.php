<?php

declare(strict_types=1);

namespace App\Http\Controllers\OAuth;

use App\Exceptions\User\OAuthExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\OAuth\OauthRedirectRequest;
use App\Models\OAuth\OAuthTypes;
use App\Services\User\UserService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Symfony\Component\HttpFoundation\Response;

class OAuthController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function redirect(OauthRedirectRequest $request): Response
    {
        return redirect(Socialite::driver($request->validated('type'))->redirect()->getTargetUrl());
    }

    public function callback(OauthRedirectRequest $request): RedirectResponse
    {
        $response  = new RedirectResponse('/');
        $oauthType = OAuthTypes::from($request->validated('type'));

        try {
            $oauthUser = Socialite::driver($oauthType->value)->user();

            $this->userService->loginWithOauth($oauthUser, $oauthType);
        } catch (InvalidStateException|OAuthExistsException $e) {
            $response->with('errors', $e->getMessage());
        } catch (Exception) {
            $response->with('errors', 'Something went wrong');
        }

        return $response;
    }
}
