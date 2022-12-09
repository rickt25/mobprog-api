<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Wallet::create([
            'user_id' => 1,
            'wallet_name' => 'Dompet Pertama',
            'balance' => 1000000,
        ]);

        Wallet::create([
            'user_id' => 1,
            'wallet_name' => 'Dompet kedua',
            'balance' => 2000000,
        ]);
    }
}
