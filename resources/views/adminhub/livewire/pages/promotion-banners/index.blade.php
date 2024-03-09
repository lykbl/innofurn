<div class="flex-col space-y-4">
    <div class="flex items-center justify-between">
        <strong class="text-lg font-bold md:text-2xl">
            {{ __('adminhub::components.promotion-banners.index.title') }}
        </strong>

        <div class="text-right">
            <x-hub::button
              tag="a"
              href="{{ route('hub.promotion-banners.create') }}"
            >
                {{ __('adminhub::components.promotion-banners.index.create_promotion_banner') }}
            </x-hub::button>
        </div>
    </div>

    <livewire:components.promotion-banner.table />
</div>
