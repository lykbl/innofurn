<x-hub::layout.bottom-panel>
	<form wire:submit.prevent="save">
		<div class="flex justify-end gap-6">
			@include('adminhub.partials.promotion-banners.status-bar')

			<x-hub::button type="submit">
				{{ __('adminhub::catalogue.promotion-banners.show.save_btn') }}
			</x-hub::button>
		</div>
	</form>
</x-hub::layout.bottom-panel>
