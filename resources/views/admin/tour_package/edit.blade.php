<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Paket Wisata') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="post" action="{{ route('admin.tour-package.update', $tourPackage) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <x-input-label for="name" value="{{ __('Nama Paket Wisata') }}" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $tourPackage->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="price" value="{{ __('Harga (Rp)') }}" />
                            <x-text-input id="price" name="price" type="number" class="mt-1 block w-full" :value="old('price', (int)$tourPackage->price)" />
                            <p class="text-sm text-gray-500 mt-1">Biarkan kosong jika gratis atau tidak ingin menampilkan harga.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <div>
                            <x-input-label for="description" value="{{ __('Deskripsi') }}" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $tourPackage->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="image" value="{{ __('Gambar (Biarkan kosong jika tidak ingin mengubah)') }}" />
                            
                            @if(!empty($tourPackage->image))
                                <div class="mt-2 mb-4">
                                    <p class="text-sm text-gray-500 mb-2">Gambar Saat Ini:</p>
                                    <div class="flex gap-2 flex-wrap">
                                        @foreach((is_array($tourPackage->image) ? $tourPackage->image : [$tourPackage->image]) as $img)
                                            <img src="{{ asset('storage/' . $img) }}" alt="Gambar Paket" class="h-32 object-cover rounded-md shadow-sm">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <input id="image" name="image[]" type="file" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" />
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>

                        <div>
                            <x-input-label for="kml_file" value="{{ __('KML File (Biarkan kosong jika tidak ingin mengubah)') }}" />
                            
                            @if($tourPackage->kml_file)
                                <div class="mt-2 mb-4">
                                    <p class="text-sm text-gray-500 mb-2">File Saat Ini: {{ basename($tourPackage->kml_file) }}</p>
                                    <a href="{{ route('admin.tour-package.kml', $tourPackage) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Kelola Konten KML (Edit Nama & Urutan)
                                    </a>
                                </div>
                            @endif
                            
                            <input id="kml_file" name="kml_file" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".kml" />
                            <x-input-error class="mt-2" :messages="$errors->get('kml_file')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                            <a href="{{ route('admin.tour-package.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
