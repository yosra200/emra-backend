<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'price',
        'sale_price',
        'is_active',
        'is_featured',
        'is_best_seller',
        'is_new_arrival',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_new_arrival' => 'boolean',
        ];
    }

    /**
     * Name in the current app locale, falling back to the other language.
     */
    protected function name(): Attribute
    {
        return Attribute::get(fn (): string => app()->getLocale() === 'en'
            ? ($this->name_en ?: $this->name_ar)
            : ($this->name_ar ?: $this->name_en));
    }

    /**
     * Description in the current app locale, falling back to the other language.
     */
    protected function description(): Attribute
    {
        return Attribute::get(fn (): ?string => app()->getLocale() === 'en'
            ? ($this->description_en ?: $this->description_ar)
            : ($this->description_ar ?: $this->description_en));
    }

    /**
     * Rounded discount percentage, or null when the product is not on sale.
     */
    protected function discountPercentage(): Attribute
    {
        return Attribute::get(function (): ?int {
            $price = (float) $this->price;
            $salePrice = (float) $this->sale_price;

            if ($price <= 0 || $salePrice <= 0 || $salePrice >= $price) {
                return null;
            }

            return (int) round((($price - $salePrice) / $price) * 100);
        });
    }

    /**
     * The price the customer actually pays.
     */
    protected function finalPrice(): Attribute
    {
        return Attribute::get(fn () => $this->discount_percentage !== null
            ? $this->sale_price
            : $this->price);
    }

    protected function inStock(): Attribute
    {
        return Attribute::get(fn (): bool => $this->variants->sum('stock') > 0);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
