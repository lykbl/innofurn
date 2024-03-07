<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold md:text-xl">
            {{ __('adminhub::catalogue.promotion-banner-types.show.title') }}
        </h1>

        @if ($this->canDelete)
            <x-hub::button theme="danger"
                           type="button"
                           wire:click="$set('deleteDialogVisible', true)">
                {{ __('adminhub::catalogue.promotion-banner-types.show.delete.btn_text') }}
            </x-hub::button>
        @endif
    </div>

    <x-hub::modal.dialog wire:model="deleteDialogVisible">
        <x-slot name="title">
            {{ __('adminhub::catalogue.promotion-banner-types.show.delete.confirm_text') }}
        </x-slot>

        <x-slot name="content">
            @if ($this->canDelete)
                {{ __('adminhub::catalogue.promotion-banner-types.show.delete.message') }}
            @else
                {{ __('adminhub::catalogue.promotion-banner-types.show.delete.disabled_message') }}
            @endif
        </x-slot>

        <x-slot name="footer">
            <div class="flex items-center justify-end space-x-4">
                <x-hub::button theme="gray"
                               type="button"
                               wire:click="$set('deleteDialogVisible', false)">
                    {{ __('adminhub::global.cancel') }}
                </x-hub::button>

                <x-hub::button wire:click="delete"
                               :disabled="!$this->canDelete">
                    {{ __('adminhub::catalogue.promotion-banner-types.show.delete.confirm_text') }}
                </x-hub::button>
            </div>
        </x-slot>
    </x-hub::modal.dialog>

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
