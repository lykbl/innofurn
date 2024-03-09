<div class="shadow sm:rounded-md">
  <div class="flex-col px-4 py-5 space-y-4 bg-white rounded-md sm:p-6">
    <header>
      <h3 class="text-lg font-medium leading-6 text-gray-900">
        {{ __('adminhub::partials.promotion-banners.basic-information.heading') }}
      </h3>

      <x-hub::input.group
        :label="__('adminhub::inputs.promotion-banner-type.label')"
        for="promotionBannerType"
      >
        <x-hub::input.select
          id="promotionBannerType"
          wire:model="promotionBanner.promotion_banner_type_id"
        >
          @foreach($this->promotionBannerTypes as $type)
            <option
              value="{{ $type->id }}"
              wire:key="{{ $type->id }}"
            >{{ $type->name }}</option>
          @endforeach
        </x-hub::input.select>
      </x-hub::input.group>
    </header>
  </div>
</div>
