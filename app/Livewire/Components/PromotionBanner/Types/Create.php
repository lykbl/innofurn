<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner\Types;

use App\Models\PromotionBanner\PromotionBannerType;
use Illuminate\View\View;
use Lunar\Models\Attribute;

class Create extends AbstractType
{
    public function mount(): void
    {
        $this->promotionBannerType = new PromotionBannerType();

        $this->selectedAttributes = Attribute::system(PromotionBannerType::class)->get();
    }

    protected function rules(): array
    {
        return [
            'promotionBannerType.name' => 'required|string|unique:'.get_class($this->promotionBannerType).',name',
        ];
    }

    public function create(): void
    {
        $this->validate();

        $this->promotionBannerType->save();

        $this->promotionBannerType->mappedAttributes()->sync($this->selectedAttributes->pluck('id')->toArray());

        $this->notify(
            __('adminhub::catalogue.promotion-banner-types.show.updated-message'),
            'hub.promotion-banner-types.index'
        );
    }

    public function render(): View
    {
        return view('adminhub.livewire.components.promotion-banner.types.create');
    }
}
