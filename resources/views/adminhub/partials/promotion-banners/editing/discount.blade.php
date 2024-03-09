<div class="shadow sm:rounded-md">
	<div class="flex-col px-4 py-5 space-y-4 bg-white sm:p-6 sm:rounded-md">
		<header>
			<h3 class="text-lg font-medium leading-6 text-gray-900">
				{{ __('adminhub::partials.promotion-banners.discount.heading') }}
			</h3>
		</header>
		<div class="space-y-4">
			<header class="flex items-center justify-between border-t pt-4">
				<h4 class="text-md font-medium text-gray-700">
					{{ __('adminhub::partials.promotion-banners.discount.search') }}
				</h4>

				@livewire('components.discount-search', [
						'selectedDiscountId' => $promotionBanner->discount_id,
						'ref' => 'selected-discount',
				])
			</header>
			@if($promotionBanner->discount)
				<div class="space-y-2">
					<div class="flex items-center px-4 py-2 text-sm border rounded">

						<div class="flex grow">
							<div class="grow flex gap-1.5 flex-wrap items-center">
								<strong
									class="text-gray-700 truncate max-w-[40ch]"
									title="{{ $promotionBanner->discount->name }}"
								>
									{{ $promotionBanner->discount->name }}
								</strong>
							</div>
							<div class="flex items-center">
								<x-hub::dropdown minimal>
										<x-slot name="options">
											<x-hub::dropdown.link
												class="flex items-center justify-between px-4 py-2 text-sm text-gray-700 border-b hover:bg-gray-50"
												:href="route('hub.discounts.show', $promotionBanner->discount->id )"
												target="_blank"
											>
												{{ __('adminhub::partials.promotion-banners.discount.search.view_discount') }}
											</x-hub::dropdown.link>

											<x-hub::dropdown.button
												wire:click.prevent="removeSelectedDiscount"
												class="flex items-center justify-between px-4 py-2 text-sm text-red-600 hover:bg-gray-50"
											>
												{{ __('adminhub::global.remove') }}
											</x-hub::dropdown.button>
										</x-slot>
								</x-hub::dropdown>
							</div>
					</div>
				</div>
				</div>
			@endif

			<x-hub::errors
				:error="$errors->first('promotionBanner.discount_id')"
				:errors="$errors"
			/>
		</div>
	</div>
</div>
