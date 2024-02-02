<div
	x-data="{ color: @entangle($attributes->wire('model')) }"
	x-init="
      picker = new Picker($refs.button);
      picker.onDone = rawColor => {
          color = rawColor.hex;
          $dispatch('input', color)
      }
  "
	wire:ignore
	{{ $attributes }}
>
	<button x-ref="button" class="py-2 px-4">
		<span
			class="block w-[16px] h-[16px] rounded-full border-2"
			:style="`background: ${color}`"
		></span>
	</button>
</div>
