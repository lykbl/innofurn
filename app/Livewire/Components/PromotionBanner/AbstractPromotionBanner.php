<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner;

use App\Models\PromotionBanner\PromotionBanner;
use App\Models\PromotionBanner\PromotionBannerType;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Lunar\Facades\DB;
use Lunar\Hub\Http\Livewire\Traits\CanExtendValidation;
use Lunar\Hub\Http\Livewire\Traits\HasImages;
use Lunar\Hub\Http\Livewire\Traits\HasSlots;
use Lunar\Hub\Http\Livewire\Traits\HasUrls;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Http\Livewire\Traits\WithAttributes;
use Lunar\Hub\Http\Livewire\Traits\WithLanguages;

abstract class AbstractPromotionBanner extends Component
{
    use CanExtendValidation;
    use HasImages;
    use HasSlots;
    use HasUrls;
    use Notifies;
    use WithAttributes;
    use WithFileUploads;
    use WithLanguages;

    public PromotionBanner $promotionBanner;

    public bool $showDeleteConfirm = false;

    public bool $showRestoreConfirm = false;

    protected function getListeners()
    {
        return array_merge(
            [
                'updatedAttributes',
                'discountSearch.selected' => 'selectDiscount',
            ],
            $this->getHasImagesListeners(),
            $this->getHasSlotsListeners()
        );
    }

    protected function getValidationMessages()
    {
        return array_merge(
            [
                'promotionBanner.discount_id.required' => 'You must select a discount.',
                'images.at_least_one_is'               => 'There must be at least one primary or banner.',
            ],
            $this->withAttributesValidationMessages(),
            $this->getExtendedValidationMessages(),
        );
    }

    protected function rules()
    {
        $baseRules = [
            'promotionBanner.status'                   => 'required|string',
            'promotionBanner.discount_id'              => 'required',
            'promotionBanner.promotion_banner_type_id' => 'required',
            'images'                                   => 'array|size:2|at_least_one_is:primary',
        ];

        return [
            ...$baseRules,
            ...$this->hasUrlsValidationRules(!$this->promotionBanner->id),
            ...$this->hasImagesValidationRules(),
            ...$this->withAttributesValidationRules(),
            ...$this->getExtendedValidationRules([
                'promotionBanner' => $this->promotionBanner,
            ]),
        ];
    }

    public function save()
    {
        $this->withValidator(function (Validator $validator): void {
            $validator->addExtension('at_least_one_is', function ($attribute, $value, $parameters, $validator) {
                $items = collect($validator->getData()[$attribute] ?? []);
                foreach ($parameters as $parameter) {
                    if (0 === $items->where($parameter, true)->count()) {
                        return false;
                    }
                }

                return true;
            });

            $validator->after(function ($validator): void {
                if ($validator->errors()->count()) {
                    $this->notify(
                        __('adminhub::validation.generic'),
                        level: 'error'
                    );
                }
            });
        })->validate(null, $this->getValidationMessages());

        $this->validateUrls();
        $isNew = !$this->promotionBanner->id;

        DB::transaction(function (): void {
            $data                                  = $this->prepareAttributeData();
            $this->promotionBanner->attribute_data = $data;
            $this->promotionBanner->save();

            $this->saveUrls();
            $this->updateImages();
            $this->updateSlots();

            $this->promotionBanner->refresh();

            $this->dispatchBrowserEvent('remove-images');
            $this->notify(__('adminhub::global.success'));
        });

        if ($isNew) {
            return redirect()->route('hub.promotion-banners.show', [
                'promotionBanner' => $this->promotionBanner->id,
            ]);
        }
    }

    public function getAvailableAttributesProperty(): Collection
    {
        $type = PromotionBannerType::find(
            $this->promotionBanner->promotion_banner_type_id
        );

        $available = $type->promotionBannerAttributes->sortBy('position')->values();

        return $available;
    }

    public function getSideMenuProperty(): Collection
    {
        return collect([
            [
                'title'      => __('adminhub::menu.promotion-banner.basic-information'),
                'id'         => 'basic-information',
                'has_errors' => $this->errorBag->hasAny([
                    'promotionBanner.promotion_banner_type_id',
                ]),
            ],
            [
                'title'      => __('adminhub::menu.promotion_banner.attributes'),
                'id'         => 'attributes',
                'has_errors' => $this->errorBag->hasAny([
                    'attributeMapping.*',
                ]),
            ],
            [
                'title'      => __('adminhub::menu.promotion_banner.discount'),
                'id'         => 'discount',
                'has_errors' => $this->errorBag->hasAny([
                    'promotionBanner.discount_id',
                ]),
            ],
            [
                'title'      => __('adminhub::menu.promotion_banner.images'),
                'id'         => 'images',
                'has_errors' => $this->errorBag->hasAny([
                    'images',
                ]),
            ],
            [
                'title'      => __('adminhub::menu.promotion_banner.urls'),
                'id'         => 'urls',
                'has_errors' => $this->errorBag->hasAny([
                    'urls',
                    'urls.*',
                ]),
            ],
        ])->reject(fn ($item) => ($item['hidden'] ?? false));
    }

    public function getAttributeDataProperty()
    {
        return $this->promotionBanner->attribute_data;
    }

    public function getSelectedDiscountProperty()
    {
        return $this->promotionBanner->discount;
    }

    public function getPromotionBannerTypesProperty(): Collection
    {
        return PromotionBannerType::get();
    }

    public function selectDiscount(int $id): void
    {
        $this->promotionBanner->discount_id = $id;
    }

    public function removeSelectedDiscount(): void
    {
        $this->promotionBanner->discount_id = null;
    }

    protected function getHasUrlsModel(): PromotionBanner
    {
        return $this->promotionBanner;
    }

    protected function getMediaModel(): PromotionBanner
    {
        return $this->promotionBanner;
    }

    protected function getSlotModel(): PromotionBanner
    {
        return $this->promotionBanner;
    }

    protected function getSlotContexts(): array
    {
        return ['promotion-banner.all'];
    }

    /**
     * Render the livewire component.
     *
     * @return \Illuminate\View\View
     */
    abstract public function render();
}
