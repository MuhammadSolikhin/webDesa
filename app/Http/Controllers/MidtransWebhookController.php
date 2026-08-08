<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function callback(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new Notification();
            
            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $order_id = $notification->order_id;
            $fraud = $notification->fraud_status;

            $trx = Transaction::where('order_id', $order_id)->first();
            
            if (!$trx) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            $trx->payment_type = $type;

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $trx->status = 'challenge';
                    } else {
                        $trx->status = 'success';
                    }
                }
            } else if ($transaction == 'settlement') {
                $trx->status = 'success';
            } else if ($transaction == 'pending') {
                $trx->status = 'pending';
            } else if ($transaction == 'deny') {
                $trx->status = 'failed';
            } else if ($transaction == 'expire') {
                $trx->status = 'expired';
            } else if ($transaction == 'cancel') {
                $trx->status = 'failed';
            }

            $trx->save();

            return response()->json(['message' => 'Callback received successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
