<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'General',
            'Food & Drinks',
            'Gift',
            'Bills',
            'Transportation',
            'Shopping',
            'Sports',
            'Salary',
            'Other',
        ];
        Category::create([
            'name' => 'Food & Drinks',
        ]);

        Category::create([
            'name' => 'Gift',
        ]);

        Category::create([
            'name' => 'Salary',
        ]);
    }
}
