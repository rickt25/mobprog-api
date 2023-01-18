<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(){
        $user = Auth::user();
        // $activities = Auth::user()->activities->load('category')
        // use category_name

        $activities = Activity::query()
                                ->where('user_id', $user->id)
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
                                ->values();

        return response()->json([
            'status' => 'success',
            'data' => $activities
        ]);
    }

    public function store(Request $request){
        $validateActivity = $request->validate([
            'category_id' => 'required',
            'activity_name' => 'required',
            'activity_type' => 'required',
            'activity_date' => 'required',
            'amount' => 'required',
            'description' => 'required',
            'wallet_id' => 'required'
        ]);

        $wallet = Wallet::find($validateActivity['wallet_id']);

        if($request->activity_type == 'expense'){
            if($wallet->balance < $validateActivity['amount']){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient balance'
                ]);
            }
            $wallet->balance -= $validateActivity['amount'];
        }else{
            $wallet->balance += $validateActivity['amount'];
        }
        $wallet->save();

        $activity = Activity::create([
            'category_id' => $validateActivity['category_id'],
            'activity_name' => $validateActivity['activity_name'],
            'activity_type' => $validateActivity['activity_type'],
            'activity_date' => $validateActivity['activity_date'],
            'amount' => $validateActivity['amount'],
            'description' => $validateActivity['description'],
            'wallet_id' => $validateActivity['wallet_id'],
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $activity,
            'message' => 'Remaining balance is ' . $wallet->balance
        ]);
    }

    public function update(Request $request, $id){
        $validateActivity = $request->validate([
            'category_id' => 'required',
            'activity_name' => 'required',
            'activity_type' => 'required',
            'activity_date' => 'required',
            'amount' => 'required',
            'wallet_id' => 'required'
        ]);

        $activity = Activity::find($id);

        $wallet = Wallet::find($activity->wallet_id);

        if($activity->activity_type == 'expense'){
            $wallet->balance += $activity->amount;
        } else {
            $wallet->balance -= $activity->amount;
        }

        if($request->activity_type == 'expense'){
            if($wallet->balance < $validateActivity['amount']){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient balance'
                ]);
            }
            $wallet->balance -= $validateActivity['amount'];
        }else{
            $wallet->balance += $validateActivity['amount'];
        }
        $wallet->save();

        $activity->update($validateActivity);

        return response()->json([
            'status' => 'success',
            'data' => $activity
        ]);
    }

    public function destroy($id){
        $activity = Activity::find($id);
        $wallet = Wallet::find($activity->wallet_id);

        if($activity->activity_type == 'income'){
            $wallet->balance -= $activity->amount;
        } else {
            $wallet->balance += $activity->amount;
        }

        $activity->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Activity deleted'
        ]);
    }
}
