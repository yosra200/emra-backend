<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name_ar' => 'طرح شيفون', 'name_en' => 'Chiffon Shawl'],
            ['name_ar' => 'طرح قطن', 'name_en' => 'Cotton Shawl'],
            ['name_ar' => 'طرح حرير', 'name_en' => 'Silk Shawl'],
            ['name_ar' => 'طرح لينن', 'name_en' => 'Linen Shawl'],
            ['name_ar' => 'أخمره', 'name_en' => 'Abaya'],
            ['name_ar' => 'مكملات حجاب', 'name_en' => 'Hijab Accessories'],
            ['name_ar' => 'أكسسوارز', 'name_en' => 'Accessories'],
        ];

        foreach ($categories as $category) {
            category::create($category);
        }
    }
}
