<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Read a single setting.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }

    /**
     * Read several settings at once, keyed by their setting key.
     *
     * @param  array<int, string>  $keys
     * @return array<string, string|null>
     */
    public static function getMany(array $keys): array
    {
        $stored = static::query()->whereIn('key', $keys)->pluck('value', 'key')->all();

        return array_replace(array_fill_keys($keys, null), $stored);
    }

    /**
     * Create or update a single setting.
     */
    public static function set(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Create or update several settings at once.
     *
     * @param  array<string, string|null>  $values
     */
    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::set($key, $value);
        }
    }
}
