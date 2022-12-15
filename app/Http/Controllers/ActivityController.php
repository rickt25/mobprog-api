<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(){
        $activities = Auth::user()->activities;

        return response()->json([
            'status' => 'success',
            'data' => $activities
        ]);
    }

    public function store(Request $request){
        $validateActivity = $request->validate([
            'activity_name' => 'required',
            'activity_type' => 'required',
            'activity_date' => 'required',
            'category_name' => 'required',
            'amount' => 'required',
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
            'activity_name' => $validateActivity['activity_name'],
            'activity_type' => $validateActivity['activity_type'],
            'activity_date' => $validateActivity['activity_date'],
            'category_name' => $validateActivity['category_name'],
            'amount' => $validateActivity['amount'],
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
            'activity_name' => 'required',
            'activity_type' => 'required',
            'activity_date' => 'required',
            'category_name' => 'required',
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
        $activity->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Activity deleted'
        ]);
    }
}
