<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Paket Wisata Berjalan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-8 text-gray-900">
                    <h4 class="text-lg font-semibold mb-4 text-gray-800">Paket Wisata Aktif Anda</h4>
                    
                    @if(isset($transactions) && $transactions->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($transactions as $trx)
                            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                @if($trx->tourPackage && $trx->tourPackage->image)
                                    <img src="{{ asset('storage/' . $trx->tourPackage->image) }}" alt="Package Image" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                        <span class="text-gray-400">Tidak ada gambar</span>
                                    </div>
                                @endif
                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="text-xl font-bold text-gray-800">{{ $trx->tourPackage->name ?? 'Paket Terhapus' }}</h5>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mb-4">Dibeli pada: {{ $trx->created_at->format('d M Y') }}</p>
                                    <p class="text-sm font-medium text-gray-700">ID Pesanan: <span class="text-gray-900">{{ $trx->order_id }}</span></p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 border-dashed rounded-xl p-10 text-center">
                            <h5 class="text-xl font-medium text-gray-900 mb-2">Tidak Ada Paket Berjalan</h5>
                            <p class="text-gray-500 mb-6">Anda belum memiliki paket wisata yang aktif. Yuk, pesan sekarang!</p>
                            <a href="{{ url('/#tour-packages') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Jelajahi Paket Wisata
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
