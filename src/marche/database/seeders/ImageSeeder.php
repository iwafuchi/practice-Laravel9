<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        for ($i = 1; $i < 7; $i++) {
            for ($j = 1; $j < 7; $j++) {
                DB::table('images')->insert([
                    [
                        'owner_id' => $i,
                        'filename' => "sample${j}.jpg",
                        'title' => null,
                        'created_at' => '2022/01/01 00:00:00'
                    ]
                ]);
            }
        }
    }
}
