<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ShopSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        for ($i = 1; $i < 7; $i++) {
            DB::table('shops')->insert([
                [
                    'owner_id' => $i,
                    'name' => 'ここに店名が入ります',
                    'information' => 'ここにお店の情報が入ります',
                    'filename' => "sample${i}.jpg",
                    'is_selling' => true,
                ]
            ]);
        }
    }
}
