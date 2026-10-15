<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'tourPackage'])->latest()->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    public function active()
    {
        $transactions = Transaction::with(['user', 'tourPackage'])->where('status', 'success')->latest()->get();
        return view('admin.transactions.active', compact('transactions'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,success,cancelled',
        ]);

        $transaction->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status pesanan ' . $transaction->order_id . ' berhasil diperbarui menjadi ' . ucfirst($request->status) . '.');
    }
}
