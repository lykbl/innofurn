<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Address;

use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RemoveAddress extends AddressMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): bool
    {
        // TODO forbid to delete the last address
        return $this->addressService->delete($args['id']);
    }
}
