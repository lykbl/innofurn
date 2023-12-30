<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Cart;

use App\Models\Cart;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class AddItem extends CartMutation
{
    /**
     * @param mixed          $root
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $resolveInfo
     *
     * @return Cart
     */
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Cart
    {
        return $this->cartService->addItem($args['productVariantId'], $args['quantity']);
    }
}
