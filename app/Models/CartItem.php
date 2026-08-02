<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_variant_id',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    /**
     * Unit price taken from the product the variant belongs to.
     */
    protected function unitPrice(): Attribute
    {
        return Attribute::get(fn (): float => (float) ($this->productVariant?->product?->final_price ?? 0));
    }

    /**
     * Line total for this item.
     */
    protected function total(): Attribute
    {
        return Attribute::get(fn (): float => $this->unit_price * $this->quantity);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
