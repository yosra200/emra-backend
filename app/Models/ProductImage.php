<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{


    /** Sub-folder under public/assets/uploads that this model's files live in. */
    public const FOLDER = 'products';

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

    protected static function booted(): void
    {
        static::deleting(function (self $productImage): void {
            $productImage->deleteFile($productImage->getRawOriginal('image'), self::FOLDER);
        });
    }

    // public function setImageAttribute($value): void
    // {
    //     $this->attributes['image'] = $this->uploadFile($value, 'products');
    // }

    // public function getImageAttribute(): string
    // {
    //     return $this->attributes['image']
    //         ? asset('assets/uploads/products/' . $this->attributes['image'])
    //         : '';
    // }

    protected function url(): Attribute
    {
        return Attribute::get(fn(): string => $this->getImagePath($this->image, self::FOLDER));
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
