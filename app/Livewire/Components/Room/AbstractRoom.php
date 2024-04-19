<?php

declare(strict_types=1);

namespace App\Livewire\Components\Room;

use App\Models\Room;
use App\Models\RoomMesh;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Lunar\Hub\Http\Livewire\Traits\CanExtendValidation;
use Lunar\Hub\Http\Livewire\Traits\HasSlots;
use Lunar\Hub\Http\Livewire\Traits\HasUrls;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Http\Livewire\Traits\WithAttributes;
use Lunar\Hub\Http\Livewire\Traits\WithLanguages;

abstract class AbstractRoom extends Component
{
    use CanExtendValidation;
    use HasSlots;
    use HasUrls;
    use Notifies;
//    use WithAttributes;
    use WithFileUploads;
    use WithLanguages;

    public Room $room;

    public $scene;

    public bool $showDeleteConfirm = false;

    public bool $showRestoreConfirm = false;

    protected function getListeners(): array
    {
        return [
            ...[
                'updatedAttributes',
            ],
           ...$this->getHasSlotsListeners(),
            'upload:finished' => 'handleUploadFinished'
        ];
    }

    protected function getValidationMessages(): array
    {
        return [
            ...[
//                'promotionBanner.discount_id.required' => 'You must select a discount.',
//                'images.at_least_one_is'               => 'There must be at least one primary or banner.',
            ],
            ...$this->withAttributesValidationMessages(),
            ...$this->getExtendedValidationMessages(),
        ];
    }

    protected function rules()
    {
        $baseRules = [
//            'room.status'                   => 'required|string',
//            'room.discount_id'              => 'required',
//            'room.promotion_banner_type_id' => 'required',
//            'images'                                   => 'array|size:2|at_least_one_is:primary,banner',
        ];

        return [
//            ...$baseRules,
//            ...$this->hasUrlsValidationRules(!$this->room->id),
//            ...$this->withAttributesValidationRules(),
//            ...$this->getExtendedValidationRules([
//                'room' => $this->room,
//            ]),
        ];
    }

//    public function save()
//    {
//        $this->withValidator(function (Validator $validator): void {
//            $validator->addExtension('at_least_one_is', function ($attribute, $value, $parameters, $validator) {
//                $items = collect($validator->getData()[$attribute] ?? []);
//                foreach ($parameters as $parameter) {
//                    if (0 === $items->where($parameter, true)->count()) {
//                        return false;
//                    }
//                }
//
//                return true;
//            });
//
//            $validator->after(function ($validator): void {
//                if ($validator->errors()->count()) {
//                    $this->notify(
//                        __('adminhub::validation.generic'),
//                        level: 'error'
//                    );
//                }
//            });
//        })->validate(null, $this->getValidationMessages());
//
//        $this->validateUrls();
//        $isNew = !$this->room->id;
//
//        DB::transaction(function (): void {
//            $data                                  = $this->prepareAttributeData();
//            $this->room->attribute_data = $data;
//            $this->room->save();
//
//            $this->saveUrls();
//            $this->updateImages();
//            $this->updateSlots();
//
//            $this->room->refresh();
//
//            $this->dispatchBrowserEvent('remove-images');
//            $this->notify(__('adminhub::global.success'));
//        });
//
//        if ($isNew) {
//            return redirect()->route('hub.promotion-banners.show', [
//                'id' => $this->room->id,
//            ]);
//        }
//    }

//    public function getAvailableAttributesProperty(): Collection
//    {
//        $type = roomType::find(
//            $this->room->promotion_banner_type_id
//        );
//
//        $available = $type->roomAttributes->sortBy('position')->values();
//
//        return $available;
//    }

    public function getSideMenuProperty(): Collection
    {
        return collect([
            [
                'title'      => __('adminhub::menu.rooms.basic-information'),
                'id'         => 'basic-information',
//                'has_errors' => $this->errorBag->hasAny([
//                    'room.promotion_banner_type_id',
//                ]),
            ],
            [
                'title'      => __('adminhub::menu.rooms.attributes'),
                'id'         => 'attributes',
                'has_errors' => $this->errorBag->hasAny([
                    'attributeMapping.*',
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
        return $this->room->attribute_data;
    }

    protected function getHasUrlsModel(): Room
    {
        return $this->room;
    }

    protected function getMeshModel(): Room
    {
        return $this->room;
    }

    protected function getSlotModel(): Room
    {
        return $this->room;
    }

    protected function getSlotContexts(): array
    {
        return ['room.all'];
    }

    public function mountHasMeshes(): void
    {
        $owner = $this->getMeshModel();

        $this->meshes = $owner->meshes()->mapWithKeys(function (RoomMesh $mesh) {
            return [
                $mesh->id => [
                    'id'        => $mesh->id,
                    'rotation'  => $mesh->rotation,
                    'position'  => $mesh->position,
                ],
            ];
        });
    }

    public function handleUploadFinished($name, array $filenames = []): void
    {
        $file = TemporaryUploadedFile::createFromLivewire($filenames[0]);
        $unzipper = new \ZipArchive();
        if ($unzipper->open($file->getRealPath())) {
            $scenePath = str_replace('.zip', '', $file->getClientOriginalName());
            $unzipper->extractTo($scenePath);
            $unzipper->close();

            $this->room->glb_location = $scenePath;
        }

    }

    abstract public function render(): View;
}
