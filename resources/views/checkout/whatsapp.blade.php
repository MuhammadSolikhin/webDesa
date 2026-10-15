<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Konfirmasi Pemesanan Paket Wisata') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8 sm:p-10 text-center">
                    <!-- WhatsApp Animated / Success Icon -->
                    <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <svg class="w-10 h-10 fill-current" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">Pesanan Anda Berhasil Dicatat!</h3>
                    <p class="text-gray-600 mb-8 max-w-lg mx-auto text-sm sm:text-base">
                        Rincian pesanan telah tersimpan di sistem. Silakan lanjutkan obrolan dengan admin kami melalui WhatsApp untuk konfirmasi ketersediaan jadwal & panduan pembayaran.
                    </p>

                    <!-- Order Summary Box -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-left mb-8 space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-sm text-gray-500">ID Pesanan</span>
                            <span class="font-mono font-bold text-gray-800">{{ $transaction->order_id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Paket Wisata</span>
                            <span class="font-semibold text-gray-900">{{ $package->name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Rencana Kunjungan</span>
                            <span class="font-medium text-gray-800">{{ $transaction->visit_date ? $transaction->visit_date->format('d F Y') : '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Jumlah Peserta</span>
                            <span class="font-medium text-gray-800">{{ $transaction->participants ?? 1 }} Orang</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">No. WhatsApp Pemesan</span>
                            <span class="font-medium text-gray-800">{{ $transaction->phone ?: '-' }}</span>
                        </div>
                        @if($transaction->notes)
                        <div class="flex flex-col pt-1">
                            <span class="text-sm text-gray-500 mb-1">Catatan Tambahan:</span>
                            <p class="text-sm text-gray-700 bg-white p-2.5 rounded border border-gray-200 italic">{{ $transaction->notes }}</p>
                        </div>
                        @endif
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                            <span class="font-bold text-gray-800">Total Biaya</span>
                            <span class="font-extrabold text-xl text-green-700">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Direct WhatsApp Action Button -->
                    <div class="space-y-4">
                        <a id="btn-whatsapp" href="{{ $waUrl }}" target="_blank" class="inline-flex items-center justify-center w-full py-4 px-8 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-6 h-6 mr-3 fill-current" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            Buka Chat WhatsApp Sekarang
                        </a>

                        <p class="text-xs text-gray-500">
                            Pesan WhatsApp telah disiapkan otomatis. Cukup kirimkan pesan tersebut untuk memulai percakapan dengan pengelola.
                        </p>
                    </div>

                    <!-- Secondary Links -->
                    <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-center gap-4 text-sm">
                        <a href="{{ route('user.transactions.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                            Lihat Riwayat Pesanan Saya
                        </a>
                        <span class="text-gray-300 hidden sm:inline">&bull;</span>
                        <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-700">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Automatic popup or redirect prompt after a brief moment
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var waLink = document.getElementById('btn-whatsapp');
                if (waLink) {
                    window.open(waLink.href, '_blank');
                }
            }, 800);
        });
    </script>
</x-app-layout>
