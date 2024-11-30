<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Models\Wallet_Transaction;

class WalletTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function wallet_topups()
    {
        $topup_transactions = WalletTransaction::where('type', 'topup')
            ->orderByDesc('id')
            ->paginate(10);
        
        return view('admin.wallet_transactions.topups', compact('topup_transactions'));
    }

    public function wallet_withdraws()
    {
        $topup_transactions = WalletTransaction::where('type', 'withdraw')
            ->orderByDesc('id')
            ->paginate(10);
        
        return view('admin.wallet_transactions.withdrawals', compact('topup_transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WalletTransaction $walletTransaction)
    {
        //
        return view('admin.wallet_transactions.details', compact('walletTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WalletTransaction $wallet_Transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WalletTransaction $wallet_Transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WalletTransaction $wallet_Transaction)
    {
        //
    }
}
