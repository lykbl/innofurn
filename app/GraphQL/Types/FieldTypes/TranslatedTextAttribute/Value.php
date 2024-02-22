<?php

declare(strict_types=1);

namespace App\GraphQL\Types\FieldTypes\TranslatedTextAttribute;

use GraphQL\Type\Definition\ResolveInfo;
use Lunar\FieldTypes\TranslatedText;
use Lunar\Models\Language;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Value
{
    public function __invoke(TranslatedText $translatedText, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?string
    {
        $translations = $translatedText->getValue();

        $fallbackLang = Language::getDefault()->code;
        $fallbackValue = null; //TODO add logic
        $settingsLangCode = 'en';
        foreach ($translations as $langCode => $translation) {
            if ($langCode === $settingsLangCode && $value = $translation?->getValue()) {
                return $value;
            }
            if ($langCode === $fallbackLang) {
                $fallbackValue = $translation->getValue();
            }
        }

        return $fallbackValue;
    }
}
