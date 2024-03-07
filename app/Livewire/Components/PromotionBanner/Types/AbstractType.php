<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner\Types;

use App\Models\PromotionBanner\PromotionBanner;
use App\Models\PromotionBanner\PromotionBannerType;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Http\Livewire\Traits\WithLanguages;
use Lunar\Models\Attribute;
use Lunar\Models\AttributeGroup;

abstract class AbstractType extends Component
{
    use Notifies;
    use WithLanguages;
    use WithPagination;

    /**
     * The current view of attributes we're assigning.
     *
     * @var string
     */
    public $view = 'promotion-banners';

    /**
     * Instance of the parent product.
     */
    public PromotionBannerType $promotionBannerType;

    /**
     * Attributes which are ready to be synced.
     */
    public Collection $selectedAttributes;

    public $attributeSearch = '';

    public function addAttribute($id): void
    {
        $this->selectedAttributes = $this->selectedAttributes->push(
            $this->getAvailableAttributes()->first(fn ($att) => $att->id == $id)
        );
    }

    public function removeAttribute($id): void
    {
        $index = $this->selectedAttributes->search(fn ($att) => $att->id == $id);

        $this->selectedAttributes->forget($index);
    }

    public function updatedAttributeSearch(): void
    {
        $this->resetPage();
    }

    public function getAttributesForGroup($groupId)
    {
        return $this->getAvailableAttributes()->filter(fn ($att) => $att->attribute_group_id == $groupId);
    }

    public function getSelectedAttributes($groupId)
    {
        return $this->selectedAttributes->filter(
            fn ($att) => $att->attribute_group_id == $groupId
        );
    }

    public function getGroups()
    {
        return AttributeGroup::whereAttributableType(PromotionBanner::class)->with([
            'attributes',
        ])->get();
    }

    public function selectAll($groupId): void
    {
        $attributes = $this->getAvailableAttributes(PromotionBanner::class)
            ->filter(fn ($att) => $att->attribute_group_id == $groupId);

        foreach ($attributes as $attribute) {
            $this->selectedAttributes->push($attribute);
        }
    }

    public function deselectAll($groupId): void
    {
        $this->selectedAttributes = $this->selectedAttributes->reject(function ($att) use ($groupId) {
            return !$att->system && $att->attribute_group_id == $groupId;
        });
    }

    public function getAvailableAttributes()
    {
        return Attribute::whereAttributeType(PromotionBanner::class)
            ->when(
                $this->attributeSearch,
                fn ($query, $search) => $query->where("name->{$this->defaultLanguage->code}", 'LIKE', '%'.$search.'%')
            )->whereSystem(false)
            ->whereNotIn('id', $this->selectedAttributes->pluck('id')->toArray())
            ->get();
    }
}
