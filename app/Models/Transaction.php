<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'tour_package_id',
        'order_id',
        'amount',
        'status',
        'snap_token',
        'payment_type',
        'phone',
        'visit_date',
        'participants',
        'notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'participants' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tourPackage(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class);
    }

    public function getWhatsAppUrlAttribute(): string
    {
        $adminPhone = \App\Models\LandingSetting::where('key', 'contact_whatsapp')->value('value')
            ?: \App\Models\LandingSetting::where('key', 'contact_phone')->value('value')
            ?: env('WHATSAPP_ADMIN_NUMBER', '6281234567890');

        $cleanPhone = preg_replace('/[^0-9]/', '', $adminPhone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        } elseif (str_starts_with($cleanPhone, '8')) {
            $cleanPhone = '62' . $cleanPhone;
        }

        $packageName = $this->tourPackage->name ?? 'Paket Wisata';
        $userName = $this->user->name ?? 'Pengunjung';
        $dateFormatted = $this->visit_date ? $this->visit_date->format('d-m-Y') : '-';
        $participants = $this->participants ?? 1;
        $amountFormatted = 'Rp ' . number_format($this->amount, 0, ',', '.');
        $notes = $this->notes ?: '-';
        $customerPhone = $this->phone ?: '-';

        $text = "Halo Admin Pengelola Wisata,\n\n"
              . "Saya ingin mengonfirmasi pemesanan paket wisata berikut:\n"
              . "• *ID Pesanan*: {$this->order_id}\n"
              . "• *Paket*: {$packageName}\n"
              . "• *Nama Pemesan*: {$userName}\n"
              . "• *No. WhatsApp*: {$customerPhone}\n"
              . "• *Tanggal Kunjungan*: {$dateFormatted}\n"
              . "• *Jumlah Peserta*: {$participants} orang\n"
              . "• *Total Biaya*: {$amountFormatted}\n"
              . "• *Catatan*: {$notes}\n\n"
              . "Mohon info ketersediaan jadwal dan tata cara pembayaran lebih lanjut. Terima kasih!";

        return "https://wa.me/{$cleanPhone}?text=" . rawurlencode($text);
    }
}
