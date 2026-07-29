<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uploadable;

class ProductImage extends Model
{
    use Uploadable;

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

    public function setImageAttribute($value): void
    {
        // Filament hands over an array when the field is hydrated from a repeater item.
        if (is_array($value)) {
            $value = reset($value) ?: null;
        }

        $previous = $this->getRawOriginal('image');
        $fileName = $this->uploadFile($value, self::FOLDER);

        // A replaced file leaves the old one orphaned on disk.
        if ($previous && $fileName && $previous !== $fileName) {
            $this->deleteFile($previous, self::FOLDER);
        }

        $this->attributes['image'] = $fileName;
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => $this->getImagePath($this->image, self::FOLDER));
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
