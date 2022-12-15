<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index(){
        $wallets = Auth::user()->wallets;

        return response()->json([
            'status' => 'success',
            'data' => $wallets
        ]);
    }

    public function store(Request $request){
        $validateWallet = $request->validate([
            'wallet_name' => 'required',
            'balance' => 'required'
        ]);

        $wallet = Wallet::create([
            'wallet_name' => $validateWallet['wallet_name'],
            'balance' => $validateWallet['balance'],
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $wallet
        ]);
    }

    public function update(Request $request, $id){
        $validateWallet = $request->validate([
            'wallet_name' => 'required',
            'balance' => 'required'
        ]);

        $wallet = Wallet::find($id);
        $wallet->update($validateWallet);

        return response()->json([
            'status' => 'success',
            'data' => $wallet
        ]);
    }

    public function destroy($id){
        $wallet = Wallet::find($id);
        $wallet->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Wallet deleted'
        ]);
    }
}
