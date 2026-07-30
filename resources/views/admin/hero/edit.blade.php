<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Hero Banner') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="post" action="{{ route('admin.hero.update', $hero) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Judul')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $hero->title)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('description', $hero->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Image -->
                        <div>
                            <x-input-label for="image" :value="__('Gambar (Kosongkan jika tidak ingin mengubah)')" />
                            @if($hero->image)
                            <div class="mt-2 mb-2">
                                <img src="{{ Storage::url($hero->image) }}" class="h-32 object-cover rounded">
                            </div>
                            @endif
                            <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('admin.hero.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Batal</a>
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
