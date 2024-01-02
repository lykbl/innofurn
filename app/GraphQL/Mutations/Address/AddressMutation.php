<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Address;

use App\GraphQL\ResolverInterface;
use App\Services\Address\AddressService;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class AddressMutation implements ResolverInterface
{
    public function __construct(protected readonly AddressService $addressService)
    {
    }

    abstract public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo);
}
