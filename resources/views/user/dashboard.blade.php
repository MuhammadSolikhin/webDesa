<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-8 text-gray-900">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="h-14 w-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}! </h3>
                            <p class="text-gray-500">Ini adalah halaman dashboard pengguna Anda.</p>
                        </div>
                    </div>
                    
                    <hr class="mb-8 border-gray-100">

                    <h4 class="text-lg font-semibold mb-4 text-gray-800">Riwayat Pemesanan Paket Wisata</h4>
                    
                    <div class="bg-gray-50 border border-gray-200 border-dashed rounded-xl p-10 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h5 class="text-xl font-medium text-gray-900 mb-2">Belum Ada Transaksi</h5>
                        <p class="text-gray-500 mb-6">Anda belum melakukan pemesanan paket wisata apapun. Ayo rencanakan liburan Anda sekarang!</p>
                        <a href="{{ url('/#tour-packages') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Jelajahi Paket Wisata
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
