<?php

declare(strict_types=1);

namespace App\GraphQL\Directives;

use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class EventDirective extends BaseDirective implements FieldMiddleware
{
    public function __construct(
        protected EventsDispatcher $eventsDispatcher,
    ) {
    }

    public static function definition(): string
    {
        return /* @lang GraphQL */ <<<'GRAPHQL'
"""
Dispatch an event after the resolution of a field.

The event constructor will be called with a single argument:
the resolved value of the field.
"""
directive @event(
  """
  Specify the fully qualified class name (FQCN) of the event to dispatch.
  """
  dispatch: String!
  
  """
  Dispatch event only for authenticated requests
  """
  authOnly: Boolean
) repeatable on FIELD_DEFINITION
GRAPHQL;
    }

    public function handleField(FieldValue $fieldValue): void
    {
        $eventClassName = $this->namespaceClassName(
            $this->directiveArgValue('dispatch'),
        );
        $authOnly = $this->directiveArgValue('authOnly', false);

        $fieldValue->resultHandler(function (mixed $result, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($eventClassName, $authOnly) {
            if ($authOnly && !$context->user()) {
                return $result;
            }

            $this->eventsDispatcher->dispatch(
                new $eventClassName($result),
            );

            return $result;
        });
    }
}
