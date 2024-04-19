<div class="flex-col space-y-4">
    <div class="flex items-center justify-between">
        <strong class="text-lg font-bold md:text-2xl">
            {{ __('adminhub::components.rooms.index.title') }}
        </strong>

        <div class="text-right">
            <x-hub::button
              tag="a"
              href="{{ route('hub.rooms.create') }}"
            >
                {{ __('adminhub::components.rooms.index.create_room') }}
            </x-hub::button>
        </div>
    </div>

    <livewire:components.room.table />
</div>
