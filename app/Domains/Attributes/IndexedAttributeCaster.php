<?php

declare(strict_types=1);

namespace App\Domains\Attributes;

use App\FieldTypes\ColorFieldType;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Lunar\FieldTypes\TranslatedText;

class IndexedAttributeCaster implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @return object|string
     */
    public static function castUsing(array $arguments)
    {
        return new class() implements CastsAttributes {
            public function get($model, $key, $value, $attributes)
            {
                return match ($value) {
                    ColorFieldType::class => IndexedAttributesTypes::COLOR->value,
                    TranslatedText::class => IndexedAttributesTypes::MULTISELECT->value,
                    default               => null,
                };
            }

            public function set($model, $key, $value, $attributes)
            {
                return match ($value) {
                    IndexedAttributesTypes::COLOR->value       => ColorFieldType::class,
                    IndexedAttributesTypes::MULTISELECT->value => TranslatedText::class,
                    default                                    => null,
                };
            }
        };
    }
}
