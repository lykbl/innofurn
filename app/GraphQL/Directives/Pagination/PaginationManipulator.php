<?php

declare(strict_types=1);

namespace App\GraphQL\Directives\Pagination;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InterfaceTypeDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\Parser;
use Nuwave\Lighthouse\Pagination\PaginationManipulator as BasePaginationManipulator;
use Nuwave\Lighthouse\Pagination\PaginationType as BasePaginationType;
use Nuwave\Lighthouse\Schema\AST\ASTHelper;

final class PaginationManipulator extends BasePaginationManipulator
{
    /**
     * Transform the definition for a field to a field with pagination.
     *
     * This makes either an offset-based Paginator or a cursor-based Connection.
     * The types in between are automatically generated and applied to the schema.
     */
    public function transformToPaginatedField(
        PaginationType|BasePaginationType $paginationType,
        FieldDefinitionNode &$fieldDefinition,
        ObjectTypeDefinitionNode|InterfaceTypeDefinitionNode &$parentType,
        ?int $defaultCount = null,
        ?int $maxCount = null,
        ?ObjectTypeDefinitionNode $edgeType = null,
    ): void {
        if (PaginationType::class === get_class($paginationType) && $paginationType->isScout()) {
            $this->registerScoutPaginator($fieldDefinition, $parentType, $paginationType, $defaultCount, $maxCount);
        } else {
            parent::transformToPaginatedField($paginationType, $fieldDefinition, $parentType, $defaultCount, $maxCount, $edgeType);
        }
    }

    protected function registerScoutPaginator(
        FieldDefinitionNode &$fieldDefinition,
        ObjectTypeDefinitionNode|InterfaceTypeDefinitionNode &$parentType,
        PaginationType $paginationType,
        ?int $defaultCount = null,
        ?int $maxCount = null,
    ): void {
        $paginatorInfoNode = $this->scoutPaginatorInfo();
        if (!isset($this->documentAST->types[$paginatorInfoNode->getName()->value])) {
            $this->documentAST->setTypeDefinition($paginatorInfoNode);
        }

        $fieldTypeName           = ASTHelper::getUnderlyingTypeName($fieldDefinition);
        $paginatorTypeName       = "{$fieldTypeName}ScoutPaginator";
        $paginatorFieldClassName = addslashes(ScoutPaginatorField::class);

        $paginatorType = Parser::objectTypeDefinition(/* @lang GraphQL */ <<<GRAPHQL
            "A paginated list of {$fieldTypeName} items."
            type {$paginatorTypeName} {
                "Pagination information about the list of items."
                paginatorInfo: ScoutPaginatorInfo! @field(resolver: "{$paginatorFieldClassName}@paginatorInfoResolver")

                "A list of {$fieldTypeName} items."
                data: [{$fieldTypeName}!]! @field(resolver: "{$paginatorFieldClassName}@dataResolver")
            }
        GRAPHQL
        );
        $this->addPaginationWrapperType($paginatorType);

        $fieldDefinition->arguments[] = Parser::inputValueDefinition(
            self::countArgument($defaultCount, $maxCount),
        );
        $fieldDefinition->arguments[] = Parser::inputValueDefinition(/* @lang GraphQL */ <<<'GRAPHQL'
            "The offset from which items are returned."
            page: Int
        GRAPHQL
        );

        $fieldDefinition->type = $this->paginationResultType($paginatorTypeName);
        $parentType->fields    = ASTHelper::mergeUniqueNodeList($parentType->fields, [$fieldDefinition], true);
    }

    protected function scoutPaginatorInfo(): ObjectTypeDefinitionNode
    {
        $this->documentAST->setTypeDefinition($this->facetDistributionInfo());

        return Parser::objectTypeDefinition(/* @lang GraphQL */ <<<GRAPHQL
            "Information about pagination using a simple paginator."
            type ScoutPaginatorInfo {
              "Number of items in the current page."
              count: Int!

              "Index of the current page."
              currentPage: Int!

              "Index of the first item in the current page."
              firstItem: Int

              "Index of the last item in the current page."
              lastItem: Int

              "Number of items per page."
              perPage: Int!

              "Are there more pages after this one?"
              hasMorePages: Boolean!
              
              "List of utilized facets"
              facets: [String!]
              
              "How items distribute between facets"
              facetDistribution: [FacetDistributionInfo!]!
            }
            GRAPHQL
        );
    }

    protected function facetDistributionInfo(): ObjectTypeDefinitionNode
    {
        return Parser::objectTypeDefinition(/* @lang GraphQL */ <<<GRAPHQL
            "Information about how items distribute between facets."
            type FacetDistributionInfo {
              "Facet level"
              level: String!
              
              "Facet name"
              name: String!
              
              "Number of items of each facet"
              count: Int!
            }
            GRAPHQL
        );
    }
}
