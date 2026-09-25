<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Unggah File WebGIS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="post" action="{{ route('admin.webgis.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Nama Layer -->
                        <div>
                            <x-input-label for="name" :value="__('Nama Layer (Misal: Fasilitas Umum, Infrastruktur)')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- File GeoJSON/KML -->
                        <div>
                            <x-input-label for="file" :value="__('File Peta (.geojson atau .kml)')" />
                            <input id="file" name="file" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".geojson,.kml,.json" required />
                            <x-input-error class="mt-2" :messages="$errors->get('file')" />
                            <p class="mt-1 text-sm text-gray-500">Unggah file berformat GeoJSON untuk ditampilkan di peta.</p>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Unggah') }}</x-primary-button>
                            <a href="{{ route('admin.webgis.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
