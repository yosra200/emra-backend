<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name_ar',
        'name_en',
    ];

    /**
     * Name in the current app locale, falling back to the other language.
     */
    protected function name(): Attribute
    {
        return Attribute::get(fn (): string => app()->getLocale() === 'en'
            ? ($this->name_en ?: $this->name_ar)
            : ($this->name_ar ?: $this->name_en));
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
