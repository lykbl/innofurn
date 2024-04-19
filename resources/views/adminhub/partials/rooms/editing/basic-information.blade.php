<div class="shadow sm:rounded-md">
  <div class="flex-col px-4 py-5 space-y-4 bg-white rounded-md sm:p-6">
    <header>
      <h3 class="text-lg font-medium leading-6 text-gray-900">
        {{ __('adminhub::partials.rooms.basic-information.heading') }}
      </h3>
    </header>

    <div class="space-y-4">
      <x-hub::input.fileupload wire:model="scene" />
    </div>

  </div>
</div>
