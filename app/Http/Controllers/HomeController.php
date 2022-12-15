<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function overview(){
        // get user total balance from wallet
        // get user total income and expense
        $wallets = Auth::user()->wallets;
        $totalBalance = 0;
        foreach($wallets as $wallet){
            $totalBalance += $wallet->balance;
        }

        $activities = Auth::user()->activities;
        $totalIncome = 0;
        $totalExpense = 0;
        foreach($activities as $activity){
            if($activity->activity_type == 'income'){
                $totalIncome += $activity->amount;
            }else{
                $totalExpense += $activity->amount;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_balance' => $totalBalance,
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense
            ]
        ]);
    }

    public function todayActivity(){
        $activities = Auth::user()->activities;
        $todayActivities = [];
        foreach($activities as $activity){
            if($activity->activity_date == date('Y-m-d')){
                array_push($todayActivities, $activity);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $todayActivities
        ]);
    }
}
