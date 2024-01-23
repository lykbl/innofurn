<div>
    Chat goes here...

    @if ($newMessageReceived)
        <div>
            I just got a message!!
        </div>
    @endif
    @foreach ($messages as $message)
        <div>
            {{ $message->id }}
            {{ $message->body }}
        </div>
    @endforeach

    @vite(['resources/js/chat-subscription-provider.js'])
</div>
