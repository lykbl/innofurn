<div class="flex-col space-y-4">
    <div class="flex items-center justify-between">
        <strong class="text-lg font-bold md:text-2xl">
            {{ __('adminhub::components.chats.show.title') }}
        </strong>
    </div>

    @livewire('components.chat-room.chat-room-provider', ['chatRoom' => $chatRoom])
</div>
