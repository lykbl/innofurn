<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner;

use App\Models\Discount;
use App\Models\PromotionBanner\PromotionBanner;
use App\Models\PromotionBanner\PromotionBannerType;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Illuminate\View\View;

use function in_array;

use Livewire\Component;
use Livewire\FileUploadConfiguration;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Lunar\Facades\DB;
use Lunar\Hub\Http\Livewire\Traits\CanExtendValidation;
use Lunar\Hub\Http\Livewire\Traits\HasImages;
use Lunar\Hub\Http\Livewire\Traits\HasSlots;
use Lunar\Hub\Http\Livewire\Traits\HasUrls;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Http\Livewire\Traits\WithAttributes;
use Lunar\Hub\Http\Livewire\Traits\WithLanguages;
use Spatie\Activitylog\Facades\LogBatch;

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

    protected function getListeners(): array
    {
        return [
            ...[
                'updatedAttributes',
                'discountSearch.selected' => 'selectDiscount',
            ],
           ...$this->getHasImagesListeners(),
           ...$this->getHasSlotsListeners(),
        ];
    }

    protected function getValidationMessages(): array
    {
        return [
            ...[
                'promotionBanner.discount_id.required' => 'You must select a discount.',
                'images.at_least_one_is'               => 'There must be at least one primary or banner.',
            ],
            ...$this->withAttributesValidationMessages(),
            ...$this->getExtendedValidationMessages(),
        ];
    }

    protected function rules()
    {
        $baseRules = [
            'promotionBanner.status'                   => 'required|string',
            'promotionBanner.discount_id'              => 'required',
            'promotionBanner.promotion_banner_type_id' => 'required',
            'images'                                   => 'array|size:2|at_least_one_is:primary,banner',
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
                'id' => $this->promotionBanner->id,
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
                'title'      => __('adminhub::menu.promotion-banners.basic-information'),
                'id'         => 'basic-information',
                'has_errors' => $this->errorBag->hasAny([
                    'promotionBanner.promotion_banner_type_id',
                ]),
            ],
            [
                'title'      => __('adminhub::menu.promotion-banners.attributes'),
                'id'         => 'attributes',
                'has_errors' => $this->errorBag->hasAny([
                    'attributeMapping.*',
                ]),
            ],
            [
                'title'      => __('adminhub::menu.promotion-banners.discount'),
                'id'         => 'discount',
                'has_errors' => $this->errorBag->hasAny([
                    'promotionBanner.discount_id',
                ]),
            ],
            [
                'title'      => __('adminhub::menu.promotion-banners.images'),
                'id'         => 'images',
                'has_errors' => $this->errorBag->hasAny([
                    'images',
                ]),
            ],
            [
                'title'      => __('adminhub::menu.promotion-banners.urls'),
                'id'         => 'urls',
                'has_errors' => $this->errorBag->hasAny([
                    'urls',
                    'urls.*',
                ]),
            ],
        ])->reject(fn ($item) => ($item['hidden'] ?? false));
    }

    public function getAttributeDataProperty(): ?Collection
    {
        return $this->promotionBanner->attribute_data;
    }

    public function getSelectedDiscountProperty(): Discount
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

    public function updateImages(): void
    {
        DB::transaction(function (): void {
            LogBatch::startBatch();

            $owner        = $this->getMediaModel();
            $imagesToSync = [];

            // Need to find any images that have been deleted.
            // We need to also get a fresh instance of the relationship
            // as we may have changes that Livewire/Eloquent might not be aware of.
            $owner->refresh()->getMedia('images')->reject(function ($media) {
                $imageIds = collect($this->images)->pluck('id')->toArray();

                return in_array($media->id, $imageIds, true);
            })->each(function ($media): void {
                $media->forceDelete();
            });

            foreach ($this->images as $key => $image) {
                $file        = null;
                $imageEdited = false;

                // edited image
                if (($image['file'] ?? false) && $image['file'] instanceof TemporaryUploadedFile) {
                    $file = $image['file'];

                    unset($this->images[$key]['file']);

                    $imageEdited = true;
                }

                if (empty($image['id']) || $imageEdited) {
                    if (!$imageEdited) {
                        $file = TemporaryUploadedFile::createFromLivewire(
                            $image['filename']
                        );
                    }

                    // after editing few times the name will get longer and eventually failed to upload
                    $filename = Str::of($file->getFilename())
                        ->beforeLast('.')
                        ->substr(0, 128)
                        ->append('.', $file->getClientOriginalExtension())
                    ;

                    if (FileUploadConfiguration::isUsingS3()) {
                        $media = $owner->addMediaFromDisk($file->getRealPath())
                            ->usingFileName((string) $filename)
                            ->toMediaCollection('images');
                    } else {
                        $media = $owner->addMedia($file->getRealPath())
                            ->usingFileName((string) $filename)
                            ->toMediaCollection('promotion-banners');
                    }

                    activity()
                        ->performedOn($owner)
                        ->withProperties(['media' => $media->toArray()])
                        ->event('added_image')
                        ->useLog('lunar')
                        ->log('added_image');

                    // Add ID for future and processing now.
                    $this->images[$key]['id'] = $media->id;

                    // reset image thumbnail
                    if ($imageEdited) {
                        $this->images[$key]['thumbnail'] = $media->getFullUrl('medium');
                        $this->images[$key]['original']  = $media->getFullUrl();
                    }

                    $image['id'] = $media->id;
                }

                $media = app(config('media-library.media_model'))::find($image['id']);

                $media->setCustomProperty('caption', $image['caption']);
                $media->setCustomProperty('primary', $image['primary']);
                $media->setCustomProperty('banner', $image['banner']);
                $media->setCustomProperty('position', $image['position']);
                $media->save();

                $imagesToSync[$media->id] = [
                    'primary' => $image['primary'],
                    'banner'  => $image['banner'],
                ];
            }
            $owner->images()->sync($imagesToSync);

            LogBatch::endBatch();
        });
    }

    public function mountHasImages(): void
    {
        $owner = $this->getMediaModel();

        $this->images = $owner->getMedia('images')->mapWithKeys(function ($media) {
            $key = Str::random();

            return [
                $key => [
                    'id'        => $media->id,
                    'sort_key'  => $key,
                    'thumbnail' => $media->getFullUrl('medium'),
                    'original'  => $media->getFullUrl(),
                    'preview'   => false,
                    'edit'      => false,
                    'caption'   => $media->getCustomProperty('caption'),
                    'primary'   => $media->getCustomProperty('primary'),
                    'banner'    => $media->getCustomProperty('banner'),
                    'position'  => $media->getCustomProperty('position', 1),
                ],
            ];
        })->sortBy('position')->toArray();
    }

    public function updatedImages($value, $key): void
    {
        $this->validate($this->hasImagesValidationRules());

        [$index, $field] = explode('.', $key);
        if (('primary' === $field || 'banner' === $field) && $value) {
            // Make sure other defaults are unchecked...
            $this->images = collect($this->images)->map(function ($image, $imageIndex) use ($index, $field) {
                $image[$field] = $index === $imageIndex;

                return $image;
            })->toArray();
        }
    }

    public function handleUploadFinished($name, array $filenames = []): void
    {
        if ('imageUploadQueue' !== $name) {
            return;
        }

        foreach ($filenames as $fileKey => $filename) {
            $file = TemporaryUploadedFile::createFromLivewire($filename);

            $sortKey = Str::random();

            $this->images[$sortKey] = [
                'thumbnail' => $file->temporaryUrl(),
                'sort_key'  => $sortKey,
                'filename'  => $filename,
                'original'  => $file->temporaryUrl(),
                'caption'   => null,
                'position'  => collect($this->images)->max('position') + 1,
                'preview'   => false,
                'edit'      => false,
                'primary'   => !count($this->images),
                'banner'    => false,
            ];

            unset($this->imageUploadQueue[$fileKey]);
        }
    }

    abstract public function render(): View;
}
