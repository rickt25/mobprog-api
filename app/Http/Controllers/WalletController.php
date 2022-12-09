<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(){
        $wallets = Wallet::all();
        return response()->json([
            'status' => 'success',
            'data' => $wallets
        ]);
    }
}
