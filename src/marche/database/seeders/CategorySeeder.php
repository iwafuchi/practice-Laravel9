<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $primaryCategories = ['小説', '漫画', '技術書'];
        $secondaryCategories = [
            '小説' => [
                '推理小説',
                'SF小説',
                'ホラー小説',
            ],
            '漫画' => [
                '少年漫画',
                '少女漫画',
                '青年漫画',
            ],
            '技術書' => [
                'PHP',
                'Javascript',
                'MySQL',
            ]
        ];
        foreach ($primaryCategories as $primaryIndex => $primaryCategory) {
            DB::table('primary_categories')->insert([
                [
                    'name' => $primaryCategory,
                    'sort_order' => $primaryIndex + 1,
                ]
            ]);

            $secondaryCategory = $secondaryCategories[$primaryCategory];
            for ($i = 0; $i < count($secondaryCategory); $i++) {
                DB::table('secondary_categories')->insert([
                    [
                        'name' => $secondaryCategory[$i],
                        'sort_order' => $i + 1,
                        'primary_category_id' => $primaryIndex + 1
                    ]
                ]);
            }
        }
    }
}
