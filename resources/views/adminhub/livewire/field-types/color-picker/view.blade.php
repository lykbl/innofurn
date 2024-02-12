<div class="flex gap-2">
	<x-hub::input.group
		for="colorLabel"
		:label="__('adminhub::fieldtypes.color-picker.label.label')"
	>
		<x-hub::input.text
			id="colorLabel"
			wire:model="{{ $field['signature'] . '.label' }}"
		/>
	</x-hub::input.group>

	<x-hub::input.group
		label="{{ __('adminhub::fieldtypes.color-picker.radio-button.label') }}"
		for="color"
		class="flex items-center"
	>
		<x-adminhub::inputs.color-picker
			id="{{ $field['id'] }}"
			wire:model="{{ $field['signature'] . '.value' }}"
		/>
	</x-hub::input.group>
</div>
