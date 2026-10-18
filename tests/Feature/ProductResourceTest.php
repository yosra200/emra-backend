<?php

namespace Tests\Feature;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
    }

    public function test_the_product_list_renders(): void
    {
        Product::factory()->create();

        Livewire::test(ListProducts::class)->assertSuccessful();
    }

    public function test_the_product_form_renders_every_field(): void
    {
        Livewire::test(CreateProduct::class)
            ->assertSuccessful()
            ->assertSchemaStateSet([
                'is_active' => true,
                'is_best_seller' => false,
                'is_new_arrival' => false,
                'is_featured' => false,
            ]);
    }

    public function test_a_product_can_be_created_with_translations_badges_and_variants(): void
    {
        $category = Category::factory()->create();
        $color = Color::factory()->create();

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'name_ar' => 'طرحة ليكرا يومية — بيچ',
                'name_en' => 'Everyday Lycra Wrap — Taupe',
                'description_ar' => 'الطرحة المثالية لكل يوم.',
                'description_en' => 'The perfect everyday wrap.',
                'slug' => 'everyday-lycra-wrap-taupe',
                'category_id' => $category->id,
                'price' => 180,
                'sale_price' => 150,
                'is_active' => true,
                'is_best_seller' => true,
                'is_new_arrival' => true,
                'variants' => [
                    ['color_id' => $color->id, 'sku' => 'LYC-TAUPE', 'stock' => 5],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $product = Product::with('variants')->firstOrFail();

        $this->assertSame('Everyday Lycra Wrap — Taupe', $product->name_en);
        $this->assertSame('طرحة ليكرا يومية — بيچ', $product->name_ar);
        $this->assertTrue($product->is_best_seller);
        $this->assertTrue($product->is_new_arrival);
        $this->assertSame(17, $product->discount_percentage);
        $this->assertSame('LYC-TAUPE', $product->variants->first()->sku);
    }

    public function test_the_slug_is_generated_from_the_english_name(): void
    {
        Livewire::test(CreateProduct::class)
            ->fillForm(['name_en' => 'Everyday Lycra Wrap'])
            ->assertSchemaStateSet(['slug' => 'everyday-lycra-wrap']);
    }

    public function test_the_sale_price_must_be_lower_than_the_price(): void
    {
        $category = Category::factory()->create();

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'name_ar' => 'طرحة',
                'name_en' => 'Wrap',
                'slug' => 'wrap',
                'category_id' => $category->id,
                'price' => 100,
                'sale_price' => 150,
            ])
            ->call('create')
            ->assertHasFormErrors(['sale_price']);
    }

    public function test_the_edit_form_loads_the_saved_record(): void
    {
        $product = Product::factory()->create();

        Livewire::test(EditProduct::class, ['record' => $product->getRouteKey()])
            ->assertSuccessful()
            ->assertSchemaStateSet([
                'name_ar' => $product->name_ar,
                'name_en' => $product->name_en,
                'slug' => $product->slug,
            ]);
    }

    public function test_the_category_form_renders_and_saves(): void
    {
        Livewire::test(CreateCategory::class)
            ->assertSuccessful()
            ->fillForm([
                'name_ar' => 'طرح ليكرا',
                'name_en' => 'Lycra',
            ])
            ->assertSchemaStateSet(['slug' => 'lycra'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categories', [
            'slug' => 'lycra',
            'name_ar' => 'طرح ليكرا',
            'name_en' => 'Lycra',
        ]);
    }
}
