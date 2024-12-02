<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopupWalletRequest;
use App\Http\Requests\StoreWithdrawWalletRequest;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function wallet()
    {
        $user = Auth::user();

        // topup wallet, withdraw wallet, wallet transactions
        $wallet_transactions = WalletTransaction::where('user_id', $user->id)
            ->orderByDesc('id')
            ->paginate(10); 
        return view('dashboard.wallet', compact('wallet_transactions'));
    }

    public function withdraw_wallet()
    {
        return view('dashboard.withdraw_wallet');
    }

    public function withdraw_wallet_store(StoreWithdrawWalletRequest $request)
    {
        $user = Auth::user();
        if ($user->wallet->balance < 100000){
            return redirect()->back()->withErrors(['error' => 'Minimum withdraw is Rp. 100.000']);
        }

        DB::transaction(function () use ($request, $user){
            $validated = $request->validated();
            if ($request->hasFile('proof')) {
                $proofPaht = $request->file('proof')->store('proofs', 'public');
                $validated['proof'] = $proofPaht;
            }

            $validated['type'] = 'withdraw';
            $validated['is_paid'] = false;
            $validated['user_id'] = $user->id;
            $validated['amount'] = $user->wallet->balance;

            $newTopupWallet = WalletTransaction::create($validated);

            $user->wallet->update([
                'balance' => 0
            ]);
        });

        return redirect()->route('dashboard.wallet');
    }

    public function topup_wallet()
    {
        return view('dashboard.topup_wallet');
    }

    public function topup_wallet_store(StoreTopupWalletRequest $request)
    {
        $user = Auth::user();

        DB::transaction(function () use ($request, $user){
            $validated = $request->validated();
            if ($request->hasFile('proof')) {
                $proofPaht = $request->file('proof')->store('proofs', 'public');
                $validated['proof'] = $proofPaht;
            }

            $validated['type'] = 'topup';
            $validated['is_paid'] = false;
            $validated['user_id'] = $user->id;

            $newTopupWallet = WalletTransaction::create($validated);
        });

        return redirect()->route('dashboard.wallet');
    }
}
