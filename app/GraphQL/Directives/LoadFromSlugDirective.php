<?php

declare(strict_types=1);

namespace App\GraphQL\Directives;

use App\Models\Url;
use Exception;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class LoadFromSlugDirective extends BaseDirective implements FieldResolver
{
    public static function definition(): string
    {
        return /* @lang GraphQL */ <<<'GRAPHQL'
"""
Load a model by its slug.
"""
directive @loadFromSlug(
  """
  Load a model by its slug.
  """
  model: String!
) on FIELD_DEFINITION
GRAPHQL;
    }

    public function resolveField(FieldValue $fieldValue): callable
    {
        return function (mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
            $model = $this->directiveArgValue('model');
            if (!class_exists($model)) {
                throw new Exception("Model class $model does not exist.");
            }

            $slug = $args['slug'] ?? null;
            if (!$slug) {
                throw new Exception('Slug argument is required.');
            }

            $url = Url::firstWhere(['slug' => $slug, 'element_type' => $model]);
            if (!$url) {
                throw new Exception('Model not found');
            }

            return $url->element;
        };
    }
}
