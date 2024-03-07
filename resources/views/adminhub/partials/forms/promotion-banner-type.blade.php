<div class="flex-col space-y-4">
    <div class="flex-col px-4 py-5 space-y-4 bg-white shadow sm:rounded-md sm:p-6">
        <x-hub::input.group label="Name"
                            for="name"
                            :error="$errors->first('promotionBannerType.name')"
                            required>
            <x-hub::input.text wire:model="promotionBannerType.name"
                               name="name"
                               id="name"
                               :error="$errors->first('promotionBannerType.name')" />
        </x-hub::input.group>
    </div>

    <div x-data="{ view: 'promotion-banners' }">
        <div class="p-6 bg-white rounded-b shadow">
            @include('adminhub.partials.promotion-banner-types.attributes')
        </div>
    </div>
</div>
