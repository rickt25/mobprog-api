<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $dates = ['activity_date'];

    public function getCategoryNameAttribute(){
        return $this->category->name;
    }

    public function wallet(){
        return $this->belongsTo(Wallet::class);
    }

    public function getWalletNameAttribute(){
        return $this->wallet->wallet_name;
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['category_name'] = $this->category_name;
        $array['wallet_name'] = $this->wallet_name;

        // format activity_date dd-mm-yyyy
        $array['activity_date'] = $this->activity_date->format('d-m-Y');

        unset($array['user_id']);
        unset($array['wallet_id']);
        unset($array['category_id']);

        return $array;
    }
}
