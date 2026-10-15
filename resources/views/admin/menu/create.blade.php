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
                            <x-input-label for="url" :value="__('URL Halaman')" />
                            <x-text-input id="url" name="url" type="text" class="mt-1 block w-full font-mono text-sm" :value="old('url')" placeholder="Contoh: /artikel atau /#latest-articles" />
                            <x-input-error class="mt-2" :messages="$errors->get('url')" />
                            
                            <!-- Quick URL Helpers -->
                            <div class="mt-2.5">
                                <span class="text-xs text-gray-500 font-semibold block mb-1">Klik pintasan halaman tujuan di bawah untuk mengisi otomatis:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    <button type="button" onclick="setQuickUrl('/artikel', 'Berita')" class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs rounded-md border border-indigo-200 transition">
                                        📰 Halaman Berita (/artikel)
                                    </button>
                                    <button type="button" onclick="setQuickUrl('/webgis', 'Peta WebGIS')" class="px-2.5 py-1 bg-green-50 hover:bg-green-100 text-green-700 text-xs rounded-md border border-green-200 transition">
                                        🗺️ Peta WebGIS (/webgis)
                                    </button>
                                    <button type="button" onclick="setQuickUrl('/#latest-articles', 'Berita Terbaru')" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded-md border border-gray-300 transition">
                                        📌 Berita di Beranda (/#latest-articles)
                                    </button>
                                    <button type="button" onclick="setQuickUrl('/#hero', 'Beranda')" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded-md border border-gray-300 transition">
                                        🏠 Beranda (/#hero)
                                    </button>
                                    <button type="button" onclick="setQuickUrl('/#about', 'Profil')" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded-md border border-gray-300 transition">
                                        ℹ️ Profil Desa (/#about)
                                    </button>
                                    <button type="button" onclick="setQuickUrl('/#tour-packages', 'Paket Wisata')" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded-md border border-gray-300 transition">
                                        🧭 Paket Wisata (/#tour-packages)
                                    </button>
                                </div>
                            </div>
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

    <script>
        function setQuickUrl(url, defaultName) {
            document.getElementById('url').value = url;
            var nameInput = document.getElementById('name');
            if (!nameInput.value || nameInput.value.trim() === '') {
                nameInput.value = defaultName;
            }
        }
    </script>
</x-app-layout>
