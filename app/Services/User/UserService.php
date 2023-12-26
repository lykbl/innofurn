<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Exceptions\User\OAuthExistsException;
use App\Models\CustomerGroups\CustomerGroup;
use App\Models\OAuth\OAuthTypes;
use App\Models\OAuth\OAuthUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Lunar\Models\Customer;

class UserService
{
    public function signUp(string $firstName, string $lastName, ?string $password, string $email): User
    {
        /** @var User $user */
        $user = User::create([
            'name'     => "$firstName $lastName",
            'password' => $password ?? Hash::make(Str::random(32)),
            'email'    => $email,
        ]);
        $user->save();

        /** @var Customer $customer */
        $customer = Customer::create([
            'first_name' => $firstName,
            'last_name'  => $lastName,
        ]);
        $customer->save();
        $customer->users()->attach($user->id);
        $customer->customerGroups()->attach(CustomerGroup::getDefault()->id);

        event(new Registered($user));

        return $user;
    }

    /**
     * @param SocialiteUser $socialiteUser
     * @param OAuthTypes    $type
     *
     * @return User
     *
     * @throws OAuthExistsException
     */
    public function loginWithOauth(SocialiteUser $socialiteUser, OAuthTypes $type): User
    {
        $user = User::where('email', $socialiteUser->getEmail())->first();

        if ($user && $user->oauthUser->type !== $type) {
            throw new OauthExistsException();
        }

        if (!$user) {
            $name                   = $socialiteUser->getName() ?? $socialiteUser->getNickname();
            [$firstName, $lastName] = 2 === count(explode(' ', $name)) ? explode(' ', $name) : [$name, ''];
            $user                   = $this->signUp($firstName, $lastName, null, $socialiteUser->getEmail());
            $oauthUser              = OAuthUser::create([
                'oauth_id' => $socialiteUser->getId(),
                'type'     => $type->value,
                'user_id'  => $user->id,
            ]);
            $oauthUser->save();
        }

        Auth::login($user);

        return $user;
    }
}
