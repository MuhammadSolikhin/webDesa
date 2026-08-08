<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('tourPackage')->where('user_id', Auth::id())->latest()->get();
        return view('user.transactions.index', compact('transactions'));
    }

    public function active()
    {
        $transactions = Transaction::with('tourPackage')->where('user_id', Auth::id())->where('status', 'success')->latest()->get();
        return view('user.transactions.active', compact('transactions'));
    }
}
