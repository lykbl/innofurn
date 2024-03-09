<div class="space-y-6">

    @include('adminhub.partials.forms.promotion-banner-type')

    <x-hub::layout.bottom-panel>
        <div class="flex justify-end">
            <form action="#"
                  method="POST"
                  wire:submit.prevent="create">
                <x-hub::button type="submit">
                    {{ __('adminhub::catalogue.promotion-banner-types.create.btn_text') }}
                </x-hub::button>
            </form>
        </div>
    </x-hub::layout.bottom-panel>
</div>
