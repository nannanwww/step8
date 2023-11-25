<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            [
                'company_name' => 'コラコーラ',
                'street_address' => 'アメリカの真ん中',
                'representative_name' => 'Colas La cola',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'ニコリ',
                'street_address' => '日本の真ん中',
                'representative_name' => 'ニし　コリ　K',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'qepsi',
                'street_address' => 'ヨーロッパの真ん中',
                'representative_name' => 'pep pepe si',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

