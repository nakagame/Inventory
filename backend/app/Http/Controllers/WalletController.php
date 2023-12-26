<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets;
use App\Models\User;

class WalletController extends Controller
{
    private $wallet;
    private $user;

    public function __construct(Wallets $wallet, User $user) {
        $this->wallet = $wallet;
        $this->user   = $user;
    }

    public function store(Request $request)
    {
        $user = $this->user->findOrFail(Auth::user()->id);

        $request->validate([
            'wallet'  => 'required|min:1'
        ]);

        if($wallet = $this->wallet->where('user_id', Auth::user()->id)->first()) {
            $wallet->amount += $request->wallet;
            $wallet->save();
        } else {
            $newWallet = $this->wallet->create([
                'user_id' => Auth::user()->id,
                'amount'  => $request->wallet,
            ]);
    
            $user->wallet_id = $newWallet->id;
            $user->save();
        }

        return redirect()->back();
    }
}