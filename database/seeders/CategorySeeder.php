<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['slug' => 'chiffon-scarves', 'name_ar' => 'طرح شيفون', 'name_en' => 'Chiffon scarves'],
            ['slug' => 'cotton-scarves', 'name_ar' => 'طرح قطن', 'name_en' => 'Cotton scarves'],
            ['slug' => 'silk-scarves', 'name_ar' => 'طرح حرير', 'name_en' => 'Silk scarves'],
            ['slug' => 'linen-scarves', 'name_ar' => 'طرح لينن', 'name_en' => 'Linen scarves'],
            ['slug' => 'khimars', 'name_ar' => 'أخمره', 'name_en' => 'Khimars'],
            ['slug' => 'hijab-essentials', 'name_ar' => 'مكملات حجاب', 'name_en' => 'Hijab essentials'],
            ['slug' => 'accessories', 'name_ar' => 'أكسسوارز', 'name_en' => 'Accessories'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category,
            );
        }
    }
}
