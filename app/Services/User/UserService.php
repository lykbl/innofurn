<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Exceptions\User\OAuthExistsException;
use App\GraphQL\Inputs\UpdateDetailsInput;
use App\GraphQL\Inputs\UpdateMeInput;
use App\Models\CustomerGroups\CustomerGroup;
use App\Models\EmailChangeHistory;
use App\Models\OAuth\OAuthTypes;
use App\Models\OAuth\OAuthUser;
use App\Models\User;
use Faker\Factory;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Lunar\Models\Customer;

class UserService
{
    public function signUp(string $email, ?string $firstName = null, ?string $lastName = null): User
    {
        $faker     = Factory::create();
        $firstName = $firstName ?? $faker->firstName;
        $lastName  = $lastName ?? $faker->lastName;

        /** @var User $user */
        $user = User::create([
            'name'     => "$firstName $lastName",
            'password' => Hash::make(Str::random(32)),
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
        Auth::login($user);

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
            throw new OAuthExistsException();
        }

        if (!$user) {
            $name                   = $socialiteUser->getName() ?? $socialiteUser->getNickname();
            [$firstName, $lastName] = 2 === count(explode(' ', $name)) ? explode(' ', $name) : [$name, ''];
            $user                   = $this->signUp($socialiteUser->getEmail(), $firstName, $lastName);
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

    public function updateMe(
        User $user,
        UpdateDetailsInput $input,
    ): User
    {
        $customer = $user->retailCustomer;

        DB::transaction(function () use ($user, $customer, $input) {
            $user->name = $input->firstName() . " ". $input->lastName();
            $user->save();

            $customer->title = $input->title();
            $customer->first_name = $input->firstName();
            $customer->last_name = $input->lastName();
            $customer->save();
        });

        return $user;
    }

    public function updateEmail(User $user, string $email): User
    {
        DB::transaction(function () use ($user, $email) {
            $user->email = $email;
            $user->email_verified_at = null;
            $user->save();

            EmailChangeHistory::where([
                ['user_id', '=', $user->id],
                ['deleted_at', '=', null],
            ])->delete();

            EmailChangeHistory::create([
                'email' => $email,
                'user_id' => $user->id,
            ]);
        });

        return $user;
    }
}
