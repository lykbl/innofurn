<div>
	<x-hub::button
		type="button"
		wire:click.prevent="$set('showBrowser', true)"
	>
		{{ __('adminhub::components.discount-search.btn') }}
	</x-hub::button>

	<x-hub::slideover
		:title="__('adminhub::components.discount-search.title')"
		wire:model="showBrowser"
	>
		<div
			class="space-y-4"
			x-data="{
        tab: 'search'
      }"
		>
			<div>
				<nav
					class="flex space-x-4"
					aria-label="Tabs"
				>
					<span class="px-3 py-2 text-sm font-medium rounded-md bg-sky-100 text-sky-700">
						{{ __('adminhub::components.discount-search.first_tab') }}
					</span>
				</nav>
			</div>

			<div x-show="tab == 'search'">
				<x-hub::input.text wire:model.debounce.300ms="searchTerm" />
				@if($this->searchTerm)
					@if($this->results->total() > $maxResults)
						<span class="block p-3 my-2 text-xs text-sky-600 rounded bg-sky-50">
              {{ __('adminhub::components.discount-search.max_results_exceeded', [
                'max' => $maxResults,
                'total' => $this->results->total()
              ]) }}
            </span>
					@endif
					<div class="mt-4 space-y-1">
							@forelse($this->results as $discount)
								<div
									class="flex w-full items-center justify-between rounded shadow-sm text-left border px-2 py-2 text-sm"
								>
									<div class="truncate">
										{{ $discount->name }}{{ $discount->deleted_at }}
									</div>
									@if ($selectedDiscountId == $discount->id)
										<button
											class="px-2 py-1 text-xs text-red-700 border border-red-200 rounded shadow-sm hover:bg-red-50"
											wire:click.prevent="removeDiscount()"
										>
											{{ __('adminhub::global.deselect') }}
										</button>
									@else
									<button
										class="px-2 py-1 text-xs text-sky-700 border border-sky-200 rounded shadow-sm hover:bg-sky-50"
										wire:click.prevent="selectDiscount('{{ $discount->id }}')"
									>
										{{ __('adminhub::global.select') }}
									</button>
									@endif
								</div>
							@empty
								{{ __('adminhub::components.discount-search.no_results') }}
							@endforelse
					</div>
				@else
					<div class="px-3 py-2 mt-4 text-sm text-gray-500 bg-gray-100 rounded">
						{{ __('adminhub::components.discount-search.pre_search_message') }}
					</div>
				@endif
			</div>
		</div>

		<x-slot name="footer">
			<x-hub::button wire:click.prevent="triggerSelect">
				{{ __('adminhub::components.discount-search.commit_btn') }}
			</x-hub::button>
		</x-slot>
	</x-hub::slideover>
</div>
