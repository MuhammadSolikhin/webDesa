<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Seluruh Transaksi Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 sm:p-8 text-gray-900">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div>
                            <h4 class="text-xl font-bold text-gray-900">Daftar Transaksi Paket Wisata</h4>
                            <p class="text-sm text-gray-500">Kelola dan konfirmasi pesanan paket wisata dari pengunjung.</p>
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r" role="alert">
                            <p class="font-medium text-sm">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(isset($transactions) && $transactions->count() > 0)
                        <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                        <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                                        <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Paket & Rencana Kunjungan</th>
                                        <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi Konfirmasi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                    @foreach($transactions as $trx)
                                    @php
                                        $cleanCustPhone = $trx->phone ? preg_replace('/[^0-9]/', '', $trx->phone) : null;
                                        if ($cleanCustPhone && str_starts_with($cleanCustPhone, '0')) {
                                            $cleanCustPhone = '62' . substr($cleanCustPhone, 1);
                                        } elseif ($cleanCustPhone && str_starts_with($cleanCustPhone, '8')) {
                                            $cleanCustPhone = '62' . $cleanCustPhone;
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <!-- Customer Info -->
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="font-bold text-gray-900">{{ $trx->user->name ?? 'User Terhapus' }}</div>
                                            <div class="text-xs text-gray-500">{{ $trx->user->email ?? '-' }}</div>
                                            @if($trx->phone)
                                                <div class="mt-1 flex items-center gap-1.5">
                                                    <span class="text-xs font-medium text-gray-700">{{ $trx->phone }}</span>
                                                    @if($cleanCustPhone)
                                                        <a href="https://wa.me/{{ $cleanCustPhone }}" target="_blank" class="inline-flex items-center text-green-600 hover:text-green-800 text-xs font-semibold" title="Chat WhatsApp Pelanggan">
                                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                                            </svg>
                                                            Chat
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Order ID & Created At -->
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <span class="font-mono font-medium text-gray-800 text-xs">{{ $trx->order_id }}</span>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $trx->created_at->format('d M Y, H:i') }}</div>
                                        </td>

                                        <!-- Tour Package Details -->
                                        <td class="px-5 py-4">
                                            <div class="font-semibold text-gray-900">{{ $trx->tourPackage->name ?? 'Paket Terhapus' }}</div>
                                            <div class="text-xs text-gray-600 mt-1 flex flex-wrap gap-x-3 gap-y-0.5">
                                                <span><strong>Tgl:</strong> {{ $trx->visit_date ? $trx->visit_date->format('d/m/Y') : '-' }}</span>
                                                <span><strong>Peserta:</strong> {{ $trx->participants ?? 1 }} Orang</span>
                                            </div>
                                            @if($trx->notes)
                                                <div class="text-xs text-gray-500 mt-1 italic max-w-xs truncate" title="{{ $trx->notes }}">
                                                    Catatan: "{{ $trx->notes }}"
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Amount -->
                                        <td class="px-5 py-4 whitespace-nowrap font-bold text-gray-900">
                                            Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            @if($trx->status == 'success')
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Sukses / Lunas
                                                </span>
                                            @elseif($trx->status == 'pending')
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Menunggu Konfirmasi
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                                    {{ ucfirst($trx->status) }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-5 py-4 whitespace-nowrap text-center">
                                            <div class="inline-flex items-center gap-1.5">
                                                @if($trx->status != 'success')
                                                    <form action="{{ route('admin.transactions.updateStatus', $trx) }}" method="POST" onsubmit="return confirm('Konfirmasi pesanan ini sebagai Lunas / Sukses?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="success">
                                                        <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors" title="Konfirmasi Lunas">
                                                            Konfirmasi
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($trx->status != 'cancelled')
                                                    <form action="{{ route('admin.transactions.updateStatus', $trx) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg border border-red-200 transition-colors" title="Batalkan Pesanan">
                                                            Batal
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($trx->status == 'cancelled')
                                                    <form action="{{ route('admin.transactions.updateStatus', $trx) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                                                            Pulihkan
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 border-dashed rounded-xl p-10 text-center">
                            <h5 class="text-xl font-medium text-gray-900 mb-2">Belum Ada Transaksi</h5>
                            <p class="text-gray-500">Sistem belum menerima pesanan paket wisata apapun dari pengguna.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
