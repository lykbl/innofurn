<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner\Types;

use App\Models\PromotionBanner\PromotionBannerType;
use Lunar\Models\Attribute;

class Create extends AbstractType
{
    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->promotionBannerType = new PromotionBannerType();

        $this->selectedAttributes = Attribute::system(PromotionBannerType::class)->get();
    }

    /**
     * Register the validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'promotionBannerType.name' => 'required|string|unique:'.get_class($this->promotionBannerType).',name',
        ];
    }

    /**
     * Method to handle product type saving.
     *
     * @return void
     */
    public function create(): void
    {
        $this->validate();

        $this->promotionBannerType->save();

        $this->promotionBannerType->mappedAttributes()->sync($this->selectedAttributes->pluck('id')->toArray());

        $this->notify(
            __('adminhub::catalogue.promotion-banner-types.show.updated_message'),
            'hub.promotion-banner-types.index'
        );
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('adminhub.livewire.components.promotion-banner.types.create');
    }
}
