<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="post" action="{{ route('admin.menu.store') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- URL -->
                        <div>
                            <x-input-label for="url" :value="__('URL')" />
                            <x-text-input id="url" name="url" type="text" class="mt-1 block w-full" :value="old('url')" />
                            <x-input-error class="mt-2" :messages="$errors->get('url')" />
                        </div>

                        <!-- Parent Menu -->
                        <div>
                            <x-input-label for="parent_id" :value="__('Parent Menu')" />
                            <select id="parent_id" name="parent_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- None (Main Menu) --</option>
                                @foreach($parentMenus as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('parent_id')" />
                        </div>

                        <!-- Order -->
                        <div>
                            <x-input-label for="order" :value="__('Order')" />
                            <x-text-input id="order" name="order" type="number" class="mt-1 block w-full" :value="old('order', 0)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('order')" />
                        </div>

                        <!-- Active -->
                        <div class="block mt-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Active') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                            <a href="{{ route('admin.menu.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
