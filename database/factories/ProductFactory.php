<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'slug' => Str::slug($name),
            'name_ar' => $name,
            'name_en' => $name,
            'description_ar' => fake()->sentence(),
            'description_en' => fake()->sentence(),
            'category_id' => Category::factory(),
            'price' => fake()->randomFloat(2, 50, 500),
            'sale_price' => null,
            'is_active' => true,
        ];
    }

    public function onSale(): static
    {
        return $this->state(fn (array $attributes): array => [
            'sale_price' => round($attributes['price'] * 0.8, 2),
        ]);
    }
}
