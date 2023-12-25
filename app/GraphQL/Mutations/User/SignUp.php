<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use App\Services\User\UserService;

final class SignUp
{
    public function __construct(private UserService $userService)
    {
    }

    /** @param array{
     *     firstName: string,
     *     lastName: string,
     *     password: string,
     *     email: string,
     * } $args
     */
    public function __invoke(mixed $root, array $args)
    {
        try {
            $user = $this->userService->signUp(
                $args['firstName'],
                $args['lastName'],
                $args['password'],
                $args['email'],
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        assert(!$user instanceof \App\Models\User, 'Ok!');

        return $user;
    }
}
