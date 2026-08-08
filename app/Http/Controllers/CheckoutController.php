<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TourPackage;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function show(TourPackage $package)
    {
        return view('checkout.index', compact('package'));
    }

    public function process(TourPackage $package)
    {
        // 1. Configure Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $amount = $package->price ?? 0;
        
        if ($amount == 0) {
            return back()->with('error', 'Paket wisata ini gratis, tidak memerlukan pembayaran.');
        }

        $orderId = 'TRX-' . time() . '-' . Auth::id() . '-' . rand(100, 999);

        // Hapus transaksi pending sebelumnya untuk paket ini agar tidak menumpuk
        Transaction::where('user_id', Auth::id())
            ->where('tour_package_id', $package->id)
            ->where('status', 'pending')
            ->delete();

        // Create transaction as pending
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'tour_package_id' => $package->id,
            'order_id' => $orderId,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => [
                [
                    'id' => $package->id,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => \Illuminate\Support\Str::limit($package->name, 50),
                ]
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            $transaction->snap_token = $snapToken;
            $transaction->save();

            return view('checkout.pay', compact('transaction', 'package', 'snapToken'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat pembayaran Midtrans: ' . $e->getMessage());
        }
    }
}
