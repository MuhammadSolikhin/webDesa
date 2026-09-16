<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Layanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="post" action="{{ route('admin.service.update', $service) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $service->title)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('description', $service->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Icon Selection -->
                        <div>
                            <x-input-label for="icon" :value="__('Pilih Icon Layanan')" />
                            <div class="grid grid-cols-6 sm:grid-cols-8 md:grid-cols-12 gap-3 mt-2">
                                @php
                                    $icons = [
                                        'bi-activity', 'bi-briefcase', 'bi-bar-chart', 'bi-binoculars', 'bi-brightness-high', 
                                        'bi-calendar4-week', 'bi-chat-square-text', 'bi-envelope', 'bi-geo-alt', 'bi-globe', 
                                        'bi-house', 'bi-info-circle', 'bi-map', 'bi-people', 'bi-card-checklist', 
                                        'bi-bounding-box-circles', 'bi-camera', 'bi-clipboard-data', 'bi-clock', 'bi-cloud', 
                                        'bi-cup-hot', 'bi-emoji-smile', 'bi-file-earmark-text', 'bi-gear', 'bi-heart', 
                                        'bi-image', 'bi-laptop', 'bi-lightning', 'bi-megaphone', 'bi-palette', 'bi-pie-chart', 
                                        'bi-pin-map', 'bi-shield-check', 'bi-shop', 'bi-star', 'bi-telephone', 'bi-truck', 
                                        'bi-wallet2', 'bi-wifi'
                                    ];
                                    
                                    // if current icon is not in the array, push it so it doesn't get lost
                                    $currentIconClass = null;
                                    if(preg_match('/class="bi (.*?)"/', $service->icon, $matches)) {
                                        $currentIconClass = $matches[1];
                                        if(!in_array($currentIconClass, $icons)) {
                                            $icons[] = $currentIconClass;
                                        }
                                    }
                                @endphp
                                @foreach($icons as $iconClass)
                                    @php $iconVal = '<i class="bi ' . $iconClass . '"></i>'; @endphp
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="icon" value="{{ $iconVal }}" class="peer sr-only" {{ old('icon', $service->icon) == $iconVal || (isset($currentIconClass) && $currentIconClass == $iconClass) ? 'checked' : '' }} required>
                                        <div class="p-3 text-center border border-gray-300 rounded-md peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-600 hover:bg-gray-50 flex justify-center items-center transition-colors">
                                            <i class="bi {{ $iconClass }} text-xl"></i>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('icon')" />
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('admin.service.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Batal</a>
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
