<div class="flex gap-2">
  <x-hub::input.group
    for="colorLabel"
    :label="__('adminhub::fieldtypes.color-picker.label.label')"
  >
    <x-hub::input.text
      id="colorLabel"
      wire:model="attribute.configuration.label"
    />
  </x-hub::input.group>

  <x-hub::input.group
    label="{{ __('adminhub::fieldtypes.color-picker.radio-button.label') }}"
    for="color"
    :error="$errors->first('attribute.configuration.color')"
    :disabled="!!$attribute->system"
    class="flex items-center"
  >
    <x-adminhub::inputs.color-picker
      id="fieldType"
      :disabled="!!$attribute->system"
      wire:model="attribute.configuration.color"
    />
  </x-hub::input.group>
</div>
