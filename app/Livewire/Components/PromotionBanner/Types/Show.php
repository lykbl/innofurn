<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner\Types;

use App\Models\PromotionBanner\PromotionBanner;
use App\Models\PromotionBanner\PromotionBannerType;
use Lunar\Facades\DB;
use Lunar\Models\Attribute;

class Show extends AbstractType
{
    public bool $deleteDialogVisible = false;

    public function mount(): void
    {
        $systemAttributes         = Attribute::system(PromotionBanner::class)->get();
        $this->selectedAttributes = $this->promotionBannerType->mappedAttributes
            ->filter(fn ($att) => PromotionBannerType::class == $att->attribute_type)
            ->merge($systemAttributes)
        ;
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
            __('adminhub::catalogue.promotion-banner-types.show.updated_message'),
            'hub.promotion-banner-types.index'
        );
    }

    public function getCanDeleteProperty()
    {
        return !$this->promotionBannerType->promotionBanners?->count();
    }

    public function delete(): void
    {
        if (!$this->canDelete) {
            $this->notify(
                __('adminhub::catalogue.promotion-banner-types.show.delete.disabled_message')
            );
            $this->deleteDialogVisible = false;

            return;
        }

        DB::transaction(fn () => $this->promotionBannerType->delete());

        $this->notify(
            __('adminhub::catalogue.promotion-banner-types.show.delete.delete_notification'),
            'hub.promotion-banner-types.index'
        );
    }

    public function render()
    {
        return view('adminhub.livewire.components.promotion-banner.types.show');
    }
}
