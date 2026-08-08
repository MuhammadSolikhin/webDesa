<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-12 text-center text-gray-900">
                    <svg class="mx-auto h-16 w-16 text-indigo-600 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    
                    <h3 class="text-3xl font-bold mb-4">Selesaikan Pembayaran Anda</h3>
                    <p class="text-gray-600 mb-8 max-w-xl mx-auto">Kami sedang memproses pesanan <strong>{{ $package->name }}</strong> sebesar <strong>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong>. Silakan klik tombol di bawah ini untuk memunculkan jendela pembayaran Midtrans.</p>
                    
                    <button id="pay-button" class="py-4 px-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full font-bold text-lg shadow-lg transition-transform transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Buka Jendela Pembayaran
                    </button>
                    
                    <div class="mt-8">
                        <a href="{{ route('user.dashboard') }}" class="text-gray-500 hover:text-gray-700">Lihat Dashboard Saya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
      document.getElementById('pay-button').onclick = function(){
        // SnapToken acquired from previous step
        snap.pay('{{ $snapToken }}', {
          // Optional
          onSuccess: function(result){
            window.location.href = "{{ route('user.dashboard') }}?status=success";
          },
          // Optional
          onPending: function(result){
            window.location.href = "{{ route('user.dashboard') }}?status=pending";
          },
          // Optional
          onError: function(result){
            window.location.href = "{{ route('user.dashboard') }}?status=error";
          }
        });
      };
      
      // Auto open modal
      window.onload = function() {
          setTimeout(function() {
              document.getElementById('pay-button').click();
          }, 500);
      }
    </script>
</x-app-layout>
