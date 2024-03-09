<div class="flex justify-between items-center">
    <div class="flex items-center gap-4">
        <a href="{{ route('hub.promotion-banners.index') }}"
           class="text-gray-600 rounded bg-gray-50 hover:bg-sky-500 hover:text-white"
           title="{{ __('adminhub::catalogue.promotion-banners.show.back_link_title') }}">
            <x-hub::icon ref="chevron-left"
                         style="solid"
                         class="w-8 h-8" />
        </a>

        <h1 class="text-xl font-bold md:text-xl">
            @if ($promotionBanner->id)
                {{ $promotionBanner->translateAttribute('name') }}
            @else
                {{ __('adminhub::global.new_promotion_banner') }}
            @endif
        </h1>
    </div>
    <div>
        <x-hub::model-url :model="$promotionBanner" :preview="$promotionBanner->status == 'draft'" />
    </div>
</div>

<div class="pb-24 mt-8 lg:gap-8 lg:flex lg:items-start">
    <div class="space-y-6 lg:flex-1">
        <div class="space-y-6">
            <div id="basic-information">
                @include('adminhub.partials.promotion-banners.editing.basic-information')
            </div>
            <div id="attributes">
                @include('adminhub::partials.attributes')
            </div>
            <div id="discount">
                @include('adminhub.partials.promotion-banners.editing.discount')
            </div>
            <div id="images">
                @include('adminhub.partials.image-manager', [
                  'existing'  => $images,
                  'wireModel' => 'imageUploadQueue',
                  'filetypes' => ['image/*'],
                ])
            </div>
            <div id="urls">
                @include('adminhub::partials.urls')
            </div>
            @if ($promotionBanner->id)
                <div
                    @class([
                        'bg-white border rounded shadow',
                        'border-red-300' => !$promotionBanner->deleted_at,
                        'border-gray-300' => $promotionBanner->deleted_at,
                    ])
                >
                    <header
                        @class([
                            'px-6 py-4 bg-white border-b rounded-t',
                            'border-red-300 text-red-700' => !$promotionBanner->deleted_at,
                            'border-gray-300 text-gray-700' => $promotionBanner->deleted_at,
                        ])
                    >
                        @if($promotionBanner->deleted_at)
                            {{ __('adminhub::inputs.restore_zone.title') }}
                        @else
                            {{ __('adminhub::inputs.danger_zone.title') }}
                        @endif

                    </header>

                    <div class="p-6 text-sm">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 lg:col-span-8">
                                <strong>
                                    @if($promotionBanner->deleted_at)
                                        {{ __('adminhub::inputs.restore_zone.label', ['model' => 'promotionBanner']) }}
                                    @else
                                        {{ __('adminhub::inputs.danger_zone.label', ['model' => 'promotionBanner']) }}
                                    @endif
                                </strong>

                                <p class="text-xs text-gray-600">
                                    @if($promotionBanner->deleted_at)
                                        {{ __('adminhub::catalogue.promotion-banners.show.restore-strapline') }}
                                    @else
                                        {{ __('adminhub::catalogue.promotion-banners.show.delete-strapline') }}
                                    @endif
                                </p>
                            </div>

                            <div class="col-span-6 text-right lg:col-span-4">
                                @if($promotionBanner->deleted_at)
                                    <x-hub::button :disabled="false"
                                                   wire:click="$set('showRestoreConfirm', true)"
                                                   type="button"
                                                   theme="green">
                                        {{ __('adminhub::global.restore') }}
                                    </x-hub::button>
                                @else
                                    <x-hub::button :disabled="false"
                                                   wire:click="$set('showDeleteConfirm', true)"
                                                   type="button"
                                                   theme="danger">
                                        {{ __('adminhub::global.delete') }}
                                    </x-hub::button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <x-hub::modal.dialog wire:model="showRestoreConfirm">
                    <x-slot name="title">
                        {{ __('adminhub::catalogue.promotion-banners.show.restore-title') }}
                    </x-slot>

                    <x-slot name="content">
                        {{ __('adminhub::catalogue.promotion-banners.show.restore-strapline') }}
                    </x-slot>

                    <x-slot name="footer">
                        <div class="flex items-center justify-end space-x-4">
                            <x-hub::button theme="gray"
                                           type="button"
                                           wire:click.prevent="$set('showRestoreConfirm', false)">
                                {{ __('adminhub::global.cancel') }}
                            </x-hub::button>

                            <x-hub::button wire:click="restore"
                                           theme="green">
                                {{ __('adminhub::catalogue.promotion-banners.show.restore-btn') }}
                            </x-hub::button>
                        </div>
                    </x-slot>
                </x-hub::modal.dialog>

                <x-hub::modal.dialog wire:model="showDeleteConfirm">
                    <x-slot name="title">
                        {{ __('adminhub::catalogue.promotion-banners.show.delete-title') }}
                    </x-slot>

                    <x-slot name="content">
                        {{ __('adminhub::catalogue.promotion-banners.show.delete-strapline') }}
                    </x-slot>

                    <x-slot name="footer">
                        <div class="flex items-center justify-end space-x-4">
                            <x-hub::button theme="gray"
                                           type="button"
                                           wire:click.prevent="$set('showDeleteConfirm', false)">
                                {{ __('adminhub::global.cancel') }}
                            </x-hub::button>

                            <x-hub::button wire:click="delete"
                                           theme="danger">
                                {{ __('adminhub::catalogue.promotion-banners.show.delete-btn') }}
                            </x-hub::button>
                        </div>
                    </x-slot>
                </x-hub::modal.dialog>
            @endif

{{--            TODO fix this--}}
{{--            <div class="pt-12 mt-12 border-t">--}}
{{--                @livewire('hub.components.activity-log-feed', [--}}
{{--                    'subject' => $promotionBanner,--}}
{{--                ])--}}
{{--            </div>--}}
        </div>
    </div>

    @include('adminhub.partials.promotion-banners.editing.nav-sidebar')
</div>

@include('adminhub.partials.promotion-banners.editing.bottom-panel')
