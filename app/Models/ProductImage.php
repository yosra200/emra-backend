<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{

    protected $fillable = [
        'product_id',
        'image',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->image
            ? Storage::disk('public')->url($this->image)
            : null);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
