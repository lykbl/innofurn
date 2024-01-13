<?php

declare(strict_types=1);

namespace App\GraphQL\Types\Staff;

use App\Models\Customer;
use GraphQL\Type\Definition\ResolveInfo;
use Lunar\Hub\Models\Staff;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class Name
{
    public function __invoke(Staff $staff, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string
    {
        return $staff->firstname . ' ' . $staff->lastname;
    }
}
