<div class="flex flex-col gap-6">
    <div class="card max-w-[100%]">
        <div class="card-body w-full">
            <div class="flex flex-col gap-2">
                @foreach ($chatMessages as $message)
                    <div
                      wire:key="message-key-{{ $message->id  }}"
                      class="card bg-secondary {{$message->isAdmin() ? 'ml-auto' : ''}}"
                    >
                        <div class="card-body">
														{{--TODO check V3--}}
                            <h3 class="card-header text-xs">{{ $message->staff_id ? $message->staff->fullName : $message->customer->fullName }}</h3>
                            <p class="text-content2">{{ $message->body }}</p>
                            <div class="card-footer">
                                <span class="text-xs">{{ $message->created_at  }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

	<form
		wire:submit.prevent="save"
		wire:keydown.debounce.300ms.ctrl.enter="save"
		wire:keydown.debounce.300ms.meta.enter="save"
	>
		<div class="form-group">
			<div class="form-field">
				<label for="message-input" class="hidden">Message</label>
  	  	<textarea
					id="message-input"
					class="textarea textarea-solid max-w-full"
					placeholder="Your message here..."
					maxlength="300"
					rows="5"
					wire:model.debounce.300ms="input"
				></textarea>
					<div class="form-field">
						<div class="form-control justify-center">
							<button
								class="rounded-lg btn btn-primary btn-block"
								type="submit"
							>
								Send message ⌘+↵
							</button>
						</div>
					</div>
			</div>
		</div>
	</form>
{{--	@vite(['resources/js/apollo-client.js'])--}}
	<script>
		document.addEventListener('DOMContentLoaded', function () {
      const chatRoomId = @js($chatRoom->id);
			const apolloClient = window.ApolloClient;
      const gql = window.gql;
      if (!apolloClient) {
        return;
			}
      const subscription = apolloClient.subscribe({
        query: gql`
        	subscription ChatRoomUpdates($chatRoomId: IntID!) {
        	    updateChatRoom(chatRoomId: $chatRoomId) {
        	        id
        	        body
        	    }
        	}
    		`,
        variables: {
          chatRoomId: chatRoomId,
        },
      }).subscribe(
        (fetchResult) => {
          const newMessage = fetchResult.data.updateChatRoom;
          if (newMessage) {
            Livewire.emit('updateChatRoom', newMessage);
          }
        },
        (e) => console.log(e),
      );
		})
	</script>
</div>
