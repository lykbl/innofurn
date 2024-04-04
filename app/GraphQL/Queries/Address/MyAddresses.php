<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Address;

use App\GraphQL\ResolverInterface;
use App\Models\Address;
use Illuminate\Database\Eloquent\Collection;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class MyAddresses implements ResolverInterface
{
    /**
     * @param mixed          $root
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $resolveInfo
     *
     * @return Collection<Address>
     */
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection
    {
        return $context->user()->retailCustomer->addresses; // TODO do this on schema level?
    }
}
