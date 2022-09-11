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
            DB::table('images')->insert([
                [
                    'owner_id' => 1,
                    'filename' => "sample${i}.jpg",
                    'title' => null,
                    'created_at' => '2022/01/01 00:00:00'
                ]
            ]);
        }
    }
}
