<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class SignUp extends UserMutation
{
    /**
     * @param array{
     *     firstName: string,
     *     lastName: string,
     *     password: string,
     *     email: string,
     * } $args
     *
     * @throws Exception
     */
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Authenticatable
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
