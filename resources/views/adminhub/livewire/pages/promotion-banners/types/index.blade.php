<div class="flex-col space-y-4">
	<div class="flex items-center justify-between">
		<strong class="text-xl font-bold md:text-2xl">
			{{ __('adminhub::catalogue.promotion-banner-types.index.title') }}
		</strong>

		<div class="text-right">
			<x-hub::button
				tag="a"
				href="{{ route('hub.promotion-banner-types.create') }}"
			>
				{{ __('adminhub::catalogue.promotion-banner-types.index.create_btn') }}
			</x-hub::button>
		</div>
	</div>

	<div>
		@livewire('components.promotion-banner.types.table')
	</div>

</div>
