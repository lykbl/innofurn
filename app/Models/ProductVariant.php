<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Review\Review;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Laravel\Scout\Searchable;
use Lunar\Base\Casts\AsAttributeData;
use Lunar\Base\Traits\HasAttributes;
use Lunar\Models\Discount;
use Lunar\Models\ProductVariant as BaseProductVariant;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Str;

class ProductVariant extends BaseProductVariant implements Translatable
{
    use HasAttributes;
    use Searchable;
    use HasRelationships;

    protected $casts = [
        'attribute_data' => AsAttributeData::class,
    ];

    public function reviews(): HasMany
    {
        return $this
            ->hasMany(Review::class, 'reviewable_id', 'id')
            ->where('reviewable_type', BaseProductVariant::class)
        ;
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getAverageRatingAttribute(): ?float
    {
        return (float) ($this->reviews()->avg('rating') ?? 0);
    }

    // TODO Is this a bug?
    public function getAttributableClassnameAttribute()
    {
        return BaseProductVariant::class;
    }

    // TODO Add actual logic
    public function getIsFeaturedAttribute()
    {
        return $this->id <= 2;
    }

    // TODO Add actual logic
    public function getIsFavoriteAttribute()
    {
        return $this->id > 2;
    }

    public function discounts()
    {
        return $this
            ->hasManyThrough(
                Discount::class,
                \Lunar\Models\DiscountPurchasable::class,
                'purchasable_id',
                'id',
                'id',
                'discount_id'
            )
            ->scopes(['active'])
            ->where('lunar_discount_purchasables.purchasable_type', BaseProductVariant::class)
        ;
    }

    public function primaryImage(): HasOneThrough
    {
        return $this->hasOneThrough(
            \Spatie\MediaLibrary\MediaCollections\Models\Media::class,
            MediaProductVariant::class,
            'product_variant_id',
            'id',
            'id',
            'media_id',
        )->where('lunar_media_product_variant.primary', true);
    }

    public function searchableAs()
    {
        return ['product_variants_index_en', 'product_variants_index_es'];
    }

    public function toSearchableArray(?string $indexName = null)
    {
        $this->load([
            'prices.currency',
            'values',
            'values.option',
        ]);
        $locale = Str::after($indexName, 'product_variants_index_');

        $name   = $this->translateAttribute('name', $locale);
        $prices = $this->prices->mapWithKeys(fn ($price) => [$price->currency->code => $price->getRawOriginal('price')])->toArray();
        // TODO harden types?

        $structuredHierarchy = [];
        /** @var \Illuminate\Support\Collection<Collection> $collectionHierarchy */
        $collectionHierarchy = $this->product->collectionHierarchy();
        foreach ($collectionHierarchy as $level => $collection) {
            //             TODO smart enough?
            $structuredHierarchy["$collection->id"] = $collection->translateAttribute('name', $locale);
        }

        /** @var Media $primaryMedia */
        $primaryMedia = $this->primaryImage;
        $conversions  = collect(['small', 'medium'])->mapWithKeys(
            fn (string $conversion) => [$conversion => $primaryMedia?->getAvailableUrl([$conversion])]
        );

        $optionFacets = [];
        /** @var ProductOptionValue $value */
        foreach ($this->values as $value) {
            /** @var ProductOption $option */
            $option                        = $value->option;
            $optionFacets[$option->handle] = $value->translate('name', $locale);
        }

        return [
            'name'                 => $name,
            'sku'                  => $this->sku,
            'prices'               => $prices,
            'rating'               => $this->getAverageRatingAttribute(),
            'options'              => $optionFacets,
            'conversions'          => $conversions,
            'product_id'           => $this->product->id,
            'collection_hierarchy' => $structuredHierarchy,
            'brand'                => $this->product->brand->name,
        ];
    }
}
