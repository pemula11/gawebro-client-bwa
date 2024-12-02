<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

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
        $withdraw_transactions = WalletTransaction::where('type', 'withdraw')
            ->orderByDesc('id')
            ->paginate(10);
        
        return view('admin.wallet_transactions.withdrawals', compact('withdraw_transactions'));
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
    public function update(Request $request, WalletTransaction $walletTransaction)
    {
        //
        $user_to_be_approved = User::where('id', $walletTransaction->user_id)->first();

        DB::transaction(function () use ($walletTransaction, $user_to_be_approved, $request) {
           
            if (strtolower($walletTransaction->type) === 'withdraw'){

                if ($request->hasFile('proof')){
                    $proofPath = $request->file('proof')->store('proofs', 'public');
                }
                $walletTransaction->update([
                    'proof' => $proofPath,
                    'is_paid' => true
                ]);
            }

            else if ($walletTransaction->type === 'topup'){
                $walletTransaction->update([
                    'is_paid' => true
                ]);

                $user_to_be_approved->wallet->increment('balance', $walletTransaction->amount);
            }
        });

        if (strtolower($walletTransaction->type) == 'withdraw'){
            return redirect()->route('admin.withdraws')->with('success', 'Withdrawal request approved successfully');
        }
        else {
            return redirect()->route('admin.topups')->with('success', 'Topup request approved successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WalletTransaction $wallet_Transaction)
    {
        //
    }
}
