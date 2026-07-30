<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Objek Wisata') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="post" action="{{ route('admin.portfolio.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Category -->
                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <select id="category" name="category" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="filter-alam" {{ old('category') == 'filter-alam' ? 'selected' : '' }}>Alam</option>
                                <option value="filter-budaya" {{ old('category') == 'filter-budaya' ? 'selected' : '' }}>Budaya</option>
                                <option value="filter-kuliner" {{ old('category') == 'filter-kuliner' ? 'selected' : '' }}>Kuliner</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category')" />
                        </div>

                        <!-- Image -->
                        <div>
                            <x-input-label for="image" :value="__('Image')" />
                            <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('admin.portfolio.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Batal</a>
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
