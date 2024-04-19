<x-hub::layout.page-menu>
	<nav
		class="space-y-2"
		aria-label="Sidebar"
		x-data="{ activeAnchorLink: '' }"
		x-init="activeAnchorLink = window.location.hash"
	>
		@foreach ($this->getSlotsByPosition('top') as $slot)
			<a
				href="#{{ $slot->handle }}"
				@class([
						'flex items-center gap-2 p-2 rounded text-gray-500',
						'hover:bg-sky-50 hover:text-sky-700' => empty(
								$this->getSlotErrorsByHandle($slot->handle)
						),
						'text-red-600 bg-red-50' => !empty(
								$this->getSlotErrorsByHandle($slot->handle)
						),
				])
				aria-current="page"
				x-data="{ linkId: '#{{ $slot->handle }}' }"
				:class="{
                       'bg-sky-50 text-sky-700 hover:text-sky-500': linkId === activeAnchorLink
                   }"
				x-on:click="activeAnchorLink = linkId"
			>
				@if (!empty($this->getSlotErrorsByHandle($slot->handle)))
					<x-hub::icon
						ref="exclamation-circle"
						class="w-4 text-red-600"
					/>
				@endif

				<span class="text-sm font-medium">
                        {{ $slot->title }}
                    </span>
			</a>
		@endforeach

		@foreach ($this->sideMenu as $item)
			<a
				href="#{{ $item['id'] }}"
				@class([
						'flex items-center gap-2 p-2 rounded text-gray-500',
						'hover:bg-sky-50 hover:text-sky-700' => empty($item['has_errors']),
						'text-red-600 bg-red-50' => !empty($item['has_errors']),
				])
				aria-current="page"
				x-data="{ linkId: '#{{ $item['id'] }}' }"
				:class="{
                       'bg-sky-50 text-sky-700 hover:text-sky-500': linkId === activeAnchorLink
                   }"
				x-on:click="activeAnchorLink = linkId"
			>
				@if (!empty($item['has_errors']))
					<x-hub::icon
						ref="exclamation-circle"
						class="w-4 text-red-600"
					/>
				@endif

				<span class="text-sm font-medium">
                        {{ $item['title'] }}
                    </span>
			</a>
		@endforeach

		@foreach ($this->getSlotsByPosition('bottom') as $slot)
			<a
				href="#{{ $slot->handle }}"
				@class([
						'flex items-center gap-2 p-2 rounded text-gray-500',
						'hover:bg-sky-50 hover:text-sky-700' => empty(
								$this->getSlotErrorsByHandle($slot->handle)
						),
						'text-red-600 bg-red-50' => !empty(
								$this->getSlotErrorsByHandle($slot->handle)
						),
				])
				aria-current="page"
				x-data="{ linkId: '#{{ $slot->handle }}' }"
				:class="{
                       'bg-sky-50 text-sky-700 hover:text-sky-500': linkId === activeAnchorLink
                   }"
				x-on:click="activeAnchorLink = linkId"
			>
				@if (!empty($this->getSlotErrorsByHandle($slot->handle)))
					<x-hub::icon
						ref="exclamation-circle"
						class="w-4 text-red-600"
					/>
				@endif

				<span class="text-sm font-medium">
                        {{ $slot->title }}
                    </span>
			</a>
		@endforeach
	</nav>
</x-hub::layout.page-menu>
