<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getAll(){
        $categories = Category::all();
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    public function addCategory(Request $request){
        $validateCategory = $request->validate([
            'category_name' => 'required',
        ]);

        $category = Category::create([
            'name' => $validateCategory['category_name'],
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $category,
            'message' => 'Success Add Category.'
        ]);
    }


}
