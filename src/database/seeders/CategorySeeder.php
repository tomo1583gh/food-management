<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            '野菜',
            '果物',
            '肉類',
            '魚介類',
            '乳製品',
            '穀物',
            '乾物',
            '調味料',
            '飲料',
            'その他',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate([
                'name' => $category,
            ]);
        }
    }
}
