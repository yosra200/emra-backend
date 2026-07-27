<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use  App\Models\Product;
use  App\Models\Color;
use  App\Models\Size;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
        'color_id',
        'size_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
