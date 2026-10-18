<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'guest_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Total number of units in the cart.
     */
    protected function totalQuantity(): Attribute
    {
        return Attribute::get(fn (): int => (int) $this->items->sum('quantity'));
    }

    /**
     * Sum of every line total in the cart.
     */
    protected function subtotal(): Attribute
    {
        return Attribute::get(fn (): float => (float) $this->items->sum(
            fn (CartItem $item): float => (float) $item->total
        ));
    }
}
