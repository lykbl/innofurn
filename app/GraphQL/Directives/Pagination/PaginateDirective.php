<?php

declare(strict_types=1);

namespace App\GraphQL\Directives\Pagination;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Laravel\Scout\Builder as ScoutBuilder;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Pagination\PaginateDirective as BasePaginateDirective;
use Nuwave\Lighthouse\Pagination\PaginationType;
use Nuwave\Lighthouse\Pagination\ZeroPerPageLengthAwarePaginator;
use Nuwave\Lighthouse\Pagination\ZeroPerPagePaginator;
use Nuwave\Lighthouse\Schema\AST\DocumentAST;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PaginateDirective extends BasePaginateDirective
{
    public function manipulateFieldDefinition(
        DocumentAST &$documentAST,
        FieldDefinitionNode &$fieldDefinition,
        ObjectTypeDefinitionNode|InterfaceTypeDefinitionNode &$parentType
    ): void {
        $this->validateMutuallyExclusiveArguments(['model', 'builder', 'resolver']);

        $paginationManipulator = new PaginationManipulator($documentAST);

        if ($this->directiveHasArgument('resolver')) {
            // This is done only for validation
            $this->getResolverFromArgument('resolver');
        } elseif ($this->directiveHasArgument('builder')) {
            // This is done only for validation
            $this->getResolverFromArgument('builder');
        } else {
            $paginationManipulator->setModelClass(
                $this->getModelClass(),
            );
        }

        $paginationManipulator->transformToPaginatedField(
            $this->paginationType(),
            $fieldDefinition,
            $parentType,
            $this->defaultCount(),
            $this->paginateMaxCount(),
        );
    }

    public function resolveField(FieldValue $fieldValue): callable
    {
        return function (mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Paginator {
            $paginationArgs = PaginationArgs::extractArgs($args, $resolveInfo, $this->paginationType(), $this->paginateMaxCount());

            if ($this->directiveHasArgument('resolver')) {
                $paginator = $this->getResolverFromArgument('resolver')($root, $args, $context, $resolveInfo);
                assert(
                    $paginator instanceof Paginator,
                    "The method referenced by the resolver argument of the @{$this->name()} directive on {$this->nodeName()} must return a Paginator.",
                );

                if (0 === $paginationArgs->first) {
                    if ($paginator instanceof LengthAwarePaginator) {
                        return new ZeroPerPageLengthAwarePaginator($paginator->total(), $paginationArgs->page);
                    }

                    return new ZeroPerPagePaginator($paginationArgs->page);
                }

                return $paginator;
            }

            if ($this->directiveHasArgument('builder')) {
                $query = $this->getResolverFromArgument('builder')($root, $args, $context, $resolveInfo);
                assert(
                    $query instanceof QueryBuilder || $query instanceof EloquentBuilder || $query instanceof ScoutBuilder || $query instanceof Relation,
                    "The method referenced by the builder argument of the @{$this->name()} directive on {$this->nodeName()} must return a Builder or Relation.",
                );
            } else {
                $query = $this->getModelClass()::query();
            }

            $query = $resolveInfo->enhanceBuilder(
                $query,
                $this->directiveArgValue('scopes', []),
                $root,
                $args,
                $context,
                $resolveInfo,
            );

            return $paginationArgs->applyToBuilder($query);
        };
    }

    protected function paginationType(): \App\GraphQL\Directives\Pagination\PaginationType
    {
        return new \App\GraphQL\Directives\Pagination\PaginationType(
            $this->directiveArgValue('type', PaginationType::PAGINATOR),
        );
    }
}
