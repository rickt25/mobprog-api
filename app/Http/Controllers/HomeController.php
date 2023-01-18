<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Activity;
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
        $user = Auth::user();

        $activities = Activity::query()
                                ->where(
                                    [
                                        ['user_id', $user->id],
                                        ['activity_date', date('Y-m-d')]
                                    ],
                                )
                                ->orderBy('activity_date', 'DESC')
                                ->get()
                                ->groupBy(function($activity){
                                    return $activity->activity_date->format('Y-m-d');
                                })
                                ->map(function($activityGroup) {
                                    return [
                                        'date' => $activityGroup->first()->activity_date->format('d M Y'),
                                        'total' => $activityGroup->where('activity_type', 'income')->sum('amount') - $activityGroup->where('activity_type', 'expense')->sum('amount'),
                                        'activities' => $activityGroup,
                                    ];
                                })
                                ->first();

                                // if no activity yet, only send the date and total

                                if(!$activities){
                                    $activities = [
                                        'date' => Carbon::now()->format('d M Y'),
                                        'total' => 0,
                                        'activities' => []
                                    ];
                                }

        return response()->json([
            'status' => 'success',
            'data' => $activities
        ]);
    }
}
