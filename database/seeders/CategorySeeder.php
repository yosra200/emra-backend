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
            'طرح شيفون',
            'طرح قطن',
            'طرح حرير',
            'طرح لينن',
            'أخمره',
            'مكملات حجاب',
            'أكسسوارز',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
