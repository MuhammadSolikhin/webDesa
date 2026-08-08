<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout Paket Wisata') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-8 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6">Ringkasan Pesanan</h3>
                    
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="w-full md:w-1/3">
                            @if($package->image)
                                <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-full h-auto rounded-lg shadow-md object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500">Tidak ada gambar</span>
                                </div>
                            @endif
                        </div>
                        <div class="w-full md:w-2/3">
                            <h4 class="text-xl font-bold text-gray-800">{{ $package->name }}</h4>
                            <p class="text-gray-600 mt-2 mb-6">{{ $package->description }}</p>
                            
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Harga Paket</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Pajak / Biaya Layanan</span>
                                    <span class="font-semibold text-gray-800">Rp 0</span>
                                </div>
                                <hr class="my-3 border-gray-300">
                                <div class="flex justify-between items-center text-lg">
                                    <span class="font-bold text-gray-800">Total Pembayaran</span>
                                    <span class="font-bold text-indigo-600">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <form action="{{ route('checkout.process', $package) }}" method="POST" class="mt-8">
                                @csrf
                                <button type="submit" class="w-full py-4 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-lg shadow-md transition-colors text-center focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Bayar Sekarang
                                </button>
                                <a href="{{ url('/#tour-packages') }}" class="block text-center mt-4 text-indigo-600 hover:text-indigo-800 font-medium">Batal & Kembali</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
