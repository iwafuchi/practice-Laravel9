<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        for ($i = 1; $i < 7; $i++) {
            DB::table('products')->insert([
                [
                    'shop_id' => $i,
                    'secondary_category_id' => $i,
                    'image1' => $i,
                ]
            ]);
        }
    }
}
