<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold md:text-xl">
            {{ __('adminhub::catalogue.promotion-banner-types.show.title') }}
        </h1>
    </div>

    @include('adminhub.partials.forms.promotion-banner-type')

    <x-hub::layout.bottom-panel>
        <div class="flex justify-end">
            <form action="#"
                  method="POST"
                  wire:submit.prevent="update">
                <x-hub::button type="submit">
                    {{ __('adminhub::catalogue.promotion-banner-types.show.btn_text') }}
                </x-hub::button>
            </form>
        </div>
    </x-hub::layout.bottom-panel>
</div>
