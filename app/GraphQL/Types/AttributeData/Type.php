<?php

declare(strict_types=1);

namespace App\GraphQL\Types\AttributeData;

use App\FieldTypes\ColorFieldType;
use GraphQL\Type\Definition\ResolveInfo;
use Lunar\Base\FieldType;
use Lunar\FieldTypes\Text;
use Lunar\FieldTypes\TranslatedText;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class Type
{
    public function __invoke(FieldType $model, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string
    {
        return match (get_class($model)) {
            ColorFieldType::class => 'color',
            Text::class           => 'text',
            TranslatedText::class => 'translatableText',
            default               => get_class($model),
        };
    }
}
