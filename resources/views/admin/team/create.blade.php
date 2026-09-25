<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Anggota Tim') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="post" action="{{ route('admin.team.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Nama -->
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <x-input-label for="position" :value="__('Jabatan / Peran')" />
                            <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" :value="old('position')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('position')" />
                        </div>

                        <!-- Foto -->
                        <div>
                            <x-input-label for="image" :value="__('Foto (Opsional)')" />
                            <input id="image" name="image" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" />
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                            <p class="mt-1 text-sm text-gray-500">Gunakan rasio foto kotak (1:1) untuk hasil terbaik.</p>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                            <a href="{{ route('admin.team.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
