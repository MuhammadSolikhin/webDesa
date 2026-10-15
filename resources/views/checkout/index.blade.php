<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pemesanan Paket Wisata') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 sm:p-10 text-gray-900">
                    <div class="flex items-center justify-between pb-6 mb-6 border-b border-gray-200">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Formulir Pemesanan</h3>
                            <p class="text-sm text-gray-500 mt-1">Lengkapi data pemesanan sebelum diarahkan ke chat WhatsApp admin pengelola.</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            <span class="w-2 h-2 me-1.5 bg-green-500 rounded-full animate-pulse"></span>
                            WhatsApp Booking
                        </span>
                    </div>
                    
                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r" role="alert">
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r" role="alert">
                            <p class="font-semibold mb-1">Periksa kembali data Anda:</p>
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                        <!-- Left Column: Package Card -->
                        <div class="md:col-span-5">
                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 sticky top-6">
                                <h4 class="font-bold text-lg text-gray-900 mb-3">Paket Pilihan</h4>
                                
                                @if(!empty($package->image))
                                    @php
                                        $images = is_array($package->image) ? $package->image : [$package->image];
                                    @endphp
                                    @if(count($images) > 0)
                                        <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $package->name }}" class="w-full h-48 rounded-xl shadow-sm object-cover mb-4">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 rounded-xl flex items-center justify-center mb-4">
                                            <span class="text-gray-400">Tidak ada gambar</span>
                                        </div>
                                    @endif
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-xl flex items-center justify-center mb-4">
                                        <span class="text-gray-400">Tidak ada gambar</span>
                                    </div>
                                @endif

                                <h5 class="text-xl font-bold text-gray-800">{{ $package->name }}</h5>
                                <p class="text-gray-600 text-sm mt-1 mb-4 line-clamp-3">{{ $package->description }}</p>
                                
                                <div class="pt-4 border-t border-gray-200 space-y-2 text-sm">
                                    <div class="flex justify-between items-center text-gray-600">
                                        <span>Harga Satuan:</span>
                                        <span class="font-semibold text-gray-900">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-gray-600">
                                        <span>Peserta:</span>
                                        <span class="font-semibold text-gray-900" id="summary-participants">1 Orang</span>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
                                        <span class="font-bold text-gray-900">Estimasi Total:</span>
                                        <span class="font-extrabold text-xl text-green-700" id="summary-total">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Booking Form -->
                        <div class="md:col-span-7">
                            <form action="{{ route('checkout.process', $package) }}" method="POST" class="space-y-5">
                                @csrf

                                <!-- Read-only User Name & Email -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemesan</label>
                                        <input type="text" value="{{ Auth::user()->name }}" class="w-full rounded-lg border-gray-200 bg-gray-100 text-gray-600 cursor-not-allowed text-sm" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Pemesan</label>
                                        <input type="email" value="{{ Auth::user()->email }}" class="w-full rounded-lg border-gray-200 bg-gray-100 text-gray-600 cursor-not-allowed text-sm" readonly>
                                    </div>
                                </div>

                                <!-- Customer WhatsApp / Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nomor WhatsApp / HP Aktif <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                                    <p class="text-xs text-gray-500 mt-1">Admin pengelola wisata akan menghubungi nomor ini jika diperlukan.</p>
                                </div>

                                <!-- Visit Date -->
                                <div>
                                    <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-1">
                                        Rencana Tanggal Kunjungan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="visit_date" name="visit_date" min="{{ date('Y-m-d') }}" value="{{ old('visit_date', date('Y-m-d', strtotime('+1 day'))) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                                </div>

                                <!-- Participants Count -->
                                <div>
                                    <label for="participants" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jumlah Peserta / Orang <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="participants" name="participants" min="1" max="500" value="{{ old('participants', 1) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                        Catatan / Permintaan Khusus <span class="text-xs text-gray-400">(Opsional)</span>
                                    </label>
                                    <textarea id="notes" name="notes" rows="3" placeholder="Contoh: Butuh penjemputan dari terminal, preferensi menu makan, permintaan pemandu khusus, dll." class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">{{ old('notes') }}</textarea>
                                </div>

                                <!-- WhatsApp Info Notice -->
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex gap-3 items-start text-xs sm:text-sm text-green-800">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                                    </svg>
                                    <p>
                                        Setelah menekan tombol di bawah, pesanan Anda akan dicatat dan Anda akan otomatis dialihkan ke obrolan personal WhatsApp admin pengelola untuk konfirmasi ketersediaan jadwal dan petunjuk pembayaran.
                                    </p>
                                </div>

                                <!-- Submit Action Button -->
                                <div class="pt-2">
                                    <button type="submit" class="w-full py-4 px-6 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                        Lanjut Chat WhatsApp Admin & Pesan
                                    </button>
                                    
                                    <a href="{{ url('/#tour-packages') }}" class="block text-center mt-4 text-sm text-gray-500 hover:text-gray-800">
                                        &larr; Batal & Kembali ke Paket Wisata
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for Live Price Calculation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var unitPrice = {{ (float) ($package->price ?? 0) }};
            var participantsInput = document.getElementById('participants');
            var summaryParticipants = document.getElementById('summary-participants');
            var summaryTotal = document.getElementById('summary-total');

            function updateTotal() {
                var count = parseInt(participantsInput.value) || 1;
                if (count < 1) count = 1;
                var total = unitPrice * count;

                summaryParticipants.textContent = count + ' Orang';
                summaryTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }

            participantsInput.addEventListener('input', updateTotal);
            participantsInput.addEventListener('change', updateTotal);
            updateTotal();
        });
    </script>
</x-app-layout>
