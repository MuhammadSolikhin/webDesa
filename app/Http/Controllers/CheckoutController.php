<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TourPackage;
use App\Models\Transaction;
use App\Models\LandingSetting;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function show(TourPackage $package)
    {
        $adminPhone = LandingSetting::where('key', 'contact_whatsapp')->value('value')
            ?: LandingSetting::where('key', 'contact_phone')->value('value')
            ?: env('WHATSAPP_ADMIN_NUMBER', '6281234567890');

        return view('checkout.index', compact('package', 'adminPhone'));
    }

    public function process(Request $request, TourPackage $package)
    {
        $request->validate([
            'phone' => 'required|string|max:30',
            'visit_date' => 'required|date|after_or_equal:today',
            'participants' => 'required|integer|min:1|max:500',
            'notes' => 'nullable|string|max:1000',
        ], [
            'phone.required' => 'Nomor WhatsApp / telepon wajib diisi untuk koordinasi.',
            'visit_date.required' => 'Tanggal rencana kunjungan wajib diisi.',
            'visit_date.after_or_equal' => 'Tanggal kunjungan tidak boleh di masa lampau.',
            'participants.required' => 'Jumlah peserta wajib diisi minimal 1 orang.',
            'participants.min' => 'Jumlah peserta minimal 1 orang.',
        ]);

        $participants = (int) $request->input('participants', 1);
        $unitPrice = $package->price ?? 0;
        $amount = $unitPrice * $participants;

        $orderId = 'TRX-' . time() . '-' . Auth::id() . '-' . rand(100, 999);

        // Hapus transaksi pending sebelumnya untuk paket ini agar tidak menumpuk
        Transaction::where('user_id', Auth::id())
            ->where('tour_package_id', $package->id)
            ->where('status', 'pending')
            ->delete();

        // Create transaction as pending with WhatsApp payment type
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'tour_package_id' => $package->id,
            'order_id' => $orderId,
            'amount' => $amount,
            'status' => 'pending',
            'payment_type' => 'whatsapp',
            'phone' => $request->phone,
            'visit_date' => $request->visit_date,
            'participants' => $participants,
            'notes' => $request->notes,
        ]);

        $waUrl = $transaction->whats_app_url;

        return view('checkout.whatsapp', compact('transaction', 'package', 'waUrl'));
    }
}
