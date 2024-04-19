<x-hub::layout.bottom-panel>
	<form wire:submit.prevent="save">
		<div class="flex justify-end gap-6">
			@include('adminhub.partials.rooms.status-bar')

			<x-hub::button type="submit">
				{{ __('adminhub::catalogue.rooms.show.save_btn') }}
			</x-hub::button>
		</div>
	</form>
</x-hub::layout.bottom-panel>
