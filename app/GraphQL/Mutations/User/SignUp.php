<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use App\Services\User\UserService;
use Exception;

final class SignUp
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /** @param array{
     *     firstName: string,
     *     lastName: string,
     *     password: string,
     *     email: string,
     * } $args
     *
     * @throws Exception
     */
    public function __invoke(mixed $root, array $args)
    {
        try {
            return $this->userService->signUp(
                $args['firstName'],
                $args['lastName'],
                $args['password'],
                $args['email'],
            );
        } catch (Exception $e) {
            throw new Exception('Something went wrong.');
        }
    }
}
