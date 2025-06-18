<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Retail',
                'subcategories' => ['Fashion', 'Electronics', 'Groceries', 'Home & Garden']
            ],
            [
                'name' => 'Technology',
                'subcategories' => ['Software', 'IT Services', 'E-commerce', 'Digital Marketing']
            ],
            [
                'name' => 'Services',
                'subcategories' => ['Consulting', 'Financial', 'Healthcare', 'Education']
            ]
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
