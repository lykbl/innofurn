<?php

declare(strict_types=1);

namespace App\GraphQL\Types\AttributeData;

use GraphQL\Type\Definition\ResolveInfo;
use Lunar\Base\FieldType;
use Lunar\FieldTypes\TranslatedText;
use Lunar\Models\Language;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class Value
{
    public function __invoke(FieldType $model, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //TODO add lang support
        $lang = $context->user?->language ?? Language::getDefault()->code;

        return match (get_class($model)) {
            TranslatedText::class => $model->getValue()->get($lang)->getValue(),
            default               => $model->getValue(),
        };
    }
}
