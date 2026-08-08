<nav :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="bg-gray-900 text-white w-64 space-y-6 py-7 px-2 fixed inset-y-0 left-0 transform md:relative md:translate-x-0 transition duration-200 ease-in-out z-20 flex flex-col min-h-screen">
    <!-- Logo -->
    <div class="px-4 flex items-center justify-between">
        <a href="{{ Auth::user()->role === 'admin' ? route('dashboard') : route('user.dashboard') }}" class="text-2xl font-extrabold text-white flex items-center space-x-2">
            <x-application-logo class="block h-8 w-auto fill-current text-white" />
            <span>{{ Auth::user()->role === 'admin' ? 'Admin' : 'Portal Desa' }}</span>
        </a>
        <button @click="sidebarOpen = false" class="md:hidden text-gray-300 hover:text-white focus:outline-none">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto mt-6 custom-scrollbar">
        <nav class="space-y-1">
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-md transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <span class="ml-2 font-medium">{{ __('Dashboard') }}</span>
                </a>
                
                <a href="{{ route('admin.landing.edit') }}" class="flex items-center px-4 py-3 rounded-md transition duration-200 {{ request()->routeIs('admin.landing.edit') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <span class="ml-2 font-medium">{{ __('Manage Landing Page') }}</span>
                </a>
                
                <a href="{{ route('admin.menu.index') }}" class="flex items-center px-4 py-3 rounded-md transition duration-200 {{ request()->routeIs('admin.menu.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <span class="ml-2 font-medium">{{ __('Manage Menus') }}</span>
                </a>
                
                <a href="{{ route('admin.service.index') }}" class="flex items-center px-4 py-3 rounded-md transition duration-200 {{ request()->routeIs('admin.service.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <span class="ml-2 font-medium">{{ __('Kelola Layanan') }}</span>
                </a>
                
                <a href="{{ route('admin.portfolio.index') }}" class="flex items-center px-4 py-3 rounded-md transition duration-200 {{ request()->routeIs('admin.portfolio.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <span class="ml-2 font-medium">{{ __('Kelola Objek Wisata') }}</span>
                </a>
                
                <a href="{{ route('admin.hero.index') }}" class="flex items-center px-4 py-3 rounded-md transition duration-200 {{ request()->routeIs('admin.hero.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <span class="ml-2 font-medium">{{ __('Kelola Hero Banner') }}</span>
                </a>
                
                <a href="{{ route('admin.tour-package.index') }}" class="flex items-center px-4 py-3 rounded-md transition duration-200 {{ request()->routeIs('admin.tour-package.*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <span class="ml-2 font-medium">{{ __('Kelola Paket Wisata') }}</span>
                </a>
            @else
                <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-3 rounded-md transition duration-200 {{ request()->routeIs('user.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <span class="ml-2 font-medium">{{ __('Dashboard') }}</span>
                </a>
            @endif
        </nav>
    </div>

    <!-- User & Settings -->
    <div class="border-t border-gray-800 px-4 py-4 mt-auto">
        <div class="flex items-center mb-4">
            <div>
                <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
            </div>
        </div>
        
        <div class="space-y-2">
            <a href="{{ route('profile.edit') }}" class="block text-sm text-gray-300 hover:text-white transition duration-200">
                {{ __('Profile') }}
            </a>
            
            <div x-data="{ showLogoutModal: false }">
                <button @click="showLogoutModal = true" type="button" class="block w-full text-left text-sm text-gray-300 hover:text-white transition duration-200 focus:outline-none">
                    {{ __('Log Out') }}
                </button>

                <!-- Logout Confirmation Modal -->
                <template x-teleport="body">
                    <div x-show="showLogoutModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <!-- Background overlay -->
                            <div x-show="showLogoutModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showLogoutModal = false"></div>

                            <!-- This element is to trick the browser into centering the modal contents. -->
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <!-- Modal panel -->
                            <div x-show="showLogoutModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Konfirmasi Logout</h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">Apakah Anda yakin ingin keluar dari akun ini?</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Ya, Logout
                                        </button>
                                    </form>
                                    <button @click="showLogoutModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Overlay -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black opacity-50 z-10 md:hidden" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-50" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-50" x-transition:leave-end="opacity-0" style="display: none;"></div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #4b5563;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #6b7280;
    }
</style>
