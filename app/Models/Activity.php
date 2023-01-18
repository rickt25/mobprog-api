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

    public function category(){
        return $this->belongsTo(Category::class);
    }

    // public function toArray()
    // {
    //     $array = parent::toArray();
    //     $array['category_name'] = $this->category_name;
    //     return $array;
    // }
}
