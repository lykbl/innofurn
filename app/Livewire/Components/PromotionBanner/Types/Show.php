<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner\Types;

use App\Models\PromotionBanner\PromotionBanner;
use Lunar\Models\Attribute;

class Show extends AbstractType
{
    public function mount(): void
    {
        $systemAttributes = Attribute::system(PromotionBanner::class)->get();
        $mappedAttributes = $this->promotionBannerType
            ->mappedAttributes
            ->filter(fn ($att) => PromotionBanner::class === $att->attribute_type)
        ;
        $this->selectedAttributes = $systemAttributes->merge($mappedAttributes);
    }

    protected function rules()
    {
        return [
            'promotionBannerType.name' => [
                'required',
                'string',
                'unique:'.get_class($this->promotionBannerType).',name,'.$this->promotionBannerType->id,
            ],
        ];
    }

    public function update(): void
    {
        $this->validate();

        $this->promotionBannerType->save();

        $this->promotionBannerType->mappedAttributes()->sync($this->selectedAttributes->pluck('id')->toArray());

        $this->notify(
            __('adminhub::catalogue.promotion-banner-types.show.updated-message'),
            'hub.promotion-banner-types.index'
        );
    }

    public function render()
    {
        return view('adminhub.livewire.components.promotion-banner.types.show');
    }
}
