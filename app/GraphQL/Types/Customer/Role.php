<?php

declare(strict_types=1);

namespace App\GraphQL\Types\Customer;

use App\Models\Customer;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class Role
{
    public function __invoke(Customer $customer, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string
    {
        return 'user';
    }
}
