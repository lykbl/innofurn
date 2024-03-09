<div class="flex-col space-y-4">
    <div class="flex items-center justify-between">
        <strong class="text-lg font-bold md:text-2xl">
            {{ __('adminhub::components.promotion-banner.show.title') }}
        </strong>
    </div>

    @livewire('components.promotion-banner.show', ['promotionBanner' => $promotionBanner])
</div>
