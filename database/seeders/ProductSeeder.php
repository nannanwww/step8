<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('products')->insert([
            'image' => 'd:\ピクチャD\00.jpg', 
            'product_name' => 'サンプル商品',
            'price' => 1000,
            'stock' => 50,
            'company' => 'サンプルカンパニー',
            'created_at' => now(),
            'updated_at' => now(),
        ]); }
}
