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

                    <form method="post" action="{{ route('admin.landing.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- About Image -->
                        <div>
                            <x-input-label for="about_image" :value="__('About Image')" />
                            @php
                                $aboutImage = $settings['about_image'] ?? 'landingPage/img/about.jpg';
                                $aboutImageUrl = str_starts_with($aboutImage, 'landingPage/') ? asset($aboutImage) : Storage::url($aboutImage);
                            @endphp
                            <div class="mt-2 mb-2">
                                <img src="{{ $aboutImageUrl }}" alt="About Image" class="w-32 h-auto rounded-md shadow-sm">
                            </div>
                            <input id="about_image" name="about_image" type="file" class="mt-1 block w-full" accept="image/*" />
                        </div>

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

                        <!-- About Points -->
                        <div>
                            <x-input-label :value="__('About Points (List Items)')" />
                            @php
                                $points = json_decode($settings['about_points'] ?? '[]', true);
                                if (!is_array($points) || count($points) === 0) {
                                    $points = ['', '', ''];
                                }
                            @endphp
                            @foreach($points as $index => $point)
                                <x-text-input name="about_points[]" type="text" class="mt-1 block w-full mb-2" :value="$point" placeholder="Point {{ $index + 1 }}" />
                            @endforeach
                            <!-- Provide one extra empty field to add more -->
                            <x-text-input name="about_points[]" type="text" class="mt-1 block w-full mb-2" value="" placeholder="New Point (Optional)" />
                            <div class="text-sm text-gray-500 mt-1">Empty fields will be ignored.</div>
                        </div>

                        <!-- About Summary -->
                        <div>
                            <x-input-label for="about_summary" :value="__('About Summary')" />
                            <textarea id="about_summary" name="about_summary" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ $settings['about_summary'] ?? '' }}</textarea>
                        </div>

                        <!-- About Video URL -->
                        <div>
                            <x-input-label for="about_video_url" :value="__('About Video URL')" />
                            <x-text-input id="about_video_url" name="about_video_url" type="url" class="mt-1 block w-full" :value="$settings['about_video_url'] ?? ''" />
                        </div>

                        <!-- About Video Text -->
                        <div>
                            <x-input-label for="about_video_text" :value="__('About Video Text')" />
                            <x-text-input id="about_video_text" name="about_video_text" type="text" class="mt-1 block w-full" :value="$settings['about_video_text'] ?? 'Watch Video'" />
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
                            <textarea id="contact_address" name="contact_address" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ $settings['contact_address'] ?? '' }}</textarea>
                        </div>

                        <!-- Social Media Links -->
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="mb-4 font-medium text-gray-900">Social Media Links</h4>
                            
                            <div class="space-y-4">
                                <!-- Contact Twitter -->
                                <div>
                                    <x-input-label for="contact_twitter" :value="__('Twitter URL')" />
                                    <x-text-input id="contact_twitter" name="contact_twitter" type="url" class="mt-1 block w-full" :value="$settings['contact_twitter'] ?? ''" />
                                </div>

                                <!-- Contact Facebook -->
                                <div>
                                    <x-input-label for="contact_facebook" :value="__('Facebook URL')" />
                                    <x-text-input id="contact_facebook" name="contact_facebook" type="url" class="mt-1 block w-full" :value="$settings['contact_facebook'] ?? ''" />
                                </div>

                                <!-- Contact Instagram -->
                                <div>
                                    <x-input-label for="contact_instagram" :value="__('Instagram URL')" />
                                    <x-text-input id="contact_instagram" name="contact_instagram" type="url" class="mt-1 block w-full" :value="$settings['contact_instagram'] ?? ''" />
                                </div>

                                <!-- Contact LinkedIn -->
                                <div>
                                    <x-input-label for="contact_linkedin" :value="__('LinkedIn URL')" />
                                    <x-text-input id="contact_linkedin" name="contact_linkedin" type="url" class="mt-1 block w-full" :value="$settings['contact_linkedin'] ?? ''" />
                                </div>
                            </div>
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
