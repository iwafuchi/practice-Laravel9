<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        for ($i = 1; $i < 7; $i++) {
            DB::table('owners')->insert([
                [
                    'name' => "test{$i}",
                    'email' => "test${i}@test.com",
                    'password' => Hash::make('password123'),
                    'created_at' => '2022/01/01 00:00:00'
                ]
            ]);
        }
    }
}
