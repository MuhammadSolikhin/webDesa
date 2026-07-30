<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Landing Page') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('status') === 'landing-settings-updated')
                        <div class="mb-4 text-sm font-medium text-green-600">
                            {{ __('Landing page settings updated successfully.') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('admin.landing.update') }}" class="space-y-6">
                        @csrf

                        <!-- About Title -->
                        <div>
                            <x-input-label for="about_title" :value="__('About Title')" />
                            <x-text-input id="about_title" name="about_title" type="text" class="mt-1 block w-full" :value="$settings['about_title'] ?? ''" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('about_title')" />
                        </div>

                        <!-- About Subtitle -->
                        <div>
                            <x-input-label for="about_subtitle" :value="__('About Subtitle')" />
                            <x-text-input id="about_subtitle" name="about_subtitle" type="text" class="mt-1 block w-full" :value="$settings['about_subtitle'] ?? ''" required />
                        </div>

                        <!-- About Description -->
                        <div>
                            <x-input-label for="about_description" :value="__('About Description')" />
                            <textarea id="about_description" name="about_description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ $settings['about_description'] ?? '' }}</textarea>
                        </div>

                        <!-- About Video URL -->
                        <div>
                            <x-input-label for="about_video_url" :value="__('About Video URL')" />
                            <x-text-input id="about_video_url" name="about_video_url" type="url" class="mt-1 block w-full" :value="$settings['about_video_url'] ?? ''" />
                        </div>

                        <!-- Contact Email -->
                        <div>
                            <x-input-label for="contact_email" :value="__('Contact Email')" />
                            <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full" :value="$settings['contact_email'] ?? ''" />
                        </div>

                        <!-- Contact Phone -->
                        <div>
                            <x-input-label for="contact_phone" :value="__('Contact Phone')" />
                            <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full" :value="$settings['contact_phone'] ?? ''" />
                        </div>
                        
                        <!-- Contact Address -->
                        <div>
                            <x-input-label for="contact_address" :value="__('Contact Address')" />
                            <x-text-input id="contact_address" name="contact_address" type="text" class="mt-1 block w-full" :value="$settings['contact_address'] ?? ''" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
