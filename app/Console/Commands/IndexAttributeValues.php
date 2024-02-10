<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Attributes\IndexedAttributeValue;
use App\Domains\ProductVariant\ProductVariant;
use Illuminate\Console\Command;

use function in_array;

use Lunar\FieldTypes\TranslatedText;

class IndexAttributeValues extends Command
{
    private const TRANSLATABLE_TYPES = [
        TranslatedText::class,
    ];

    protected $signature = 'attributes:index';

    protected $description = 'Index product variant attribute values';

    public function handle(): void
    {
        $attributeAggregates = ProductVariant::query()
            ->select([
                'lunar_product_variants.attribute_data',
                'lunar_products.product_type_id',
                'lunar_attributables.id',
                'lunar_attributes.handle',
                'lunar_attributes.type',
            ])
            ->join('lunar_products', 'lunar_products.id', '=', 'lunar_product_variants.product_id')
            ->join('lunar_attributables', 'lunar_attributables.attributable_id', '=', 'lunar_products.product_type_id')
            ->join('lunar_attributes', 'lunar_attributables.attribute_id', '=', 'lunar_attributes.id')
            ->where('lunar_attributes.filterable', true)
            ->get()
        ;

        foreach ($attributeAggregates as $attributeAggregate) {
            $attribute = $attributeAggregate->attribute_data->get($attributeAggregate->handle);
            $langCode  = '';
            $type      = $this->attributeTypeToString($attributeAggregate->type);

            if ($this->isTranslatableType($attributeAggregate->type)) {
                foreach ($attribute->getValue() as $langCode => $translation) {
                    if (!$translation) {
                        continue;
                    }

                    $this->createIndexedAttributeValue($attributeAggregate->id, $attributeAggregate->product_type_id, $type, $langCode, $translation->getValue());
                }
            } else {
                $this->createIndexedAttributeValue($attributeAggregate->id, $attributeAggregate->product_type_id, $type, $langCode, $attribute->getValue());
            }
        }
    }

    private function createIndexedAttributeValue(int $attributableId, int $productTypeId, string $type, string $languageCode, string $value): void
    {
        IndexedAttributeValue::query()->updateOrCreate([
            'attributable_id' => $attributableId,
            'product_type_id' => $productTypeId,
            'language_code'   => $languageCode,
            'value'           => $value,
        ]);
    }

    private function isTranslatableType(string $type): bool
    {
        return in_array($type, self::TRANSLATABLE_TYPES, true);
    }
}
