<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Address;

use App\Models\Address;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AddAddress extends AddressMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Address
    {
        return $this->addressService->create($args['input']);
    }
}
