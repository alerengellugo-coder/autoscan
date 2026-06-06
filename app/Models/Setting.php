<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'group',
        'description',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getTypedValueAttribute(): mixed
    {
        $value = $this->value;

        if (is_string($value)) {
            if ($value === 'true') {
                return true;
            }

            if ($value === 'false') {
                return false;
            }

            if ($value === 'null') {
                return null;
            }

            if (ctype_digit($value) && strlen($value) === strlen((string) (int) $value)) {
                return (int) $value;
            }

            if (is_numeric($value)) {
                return (float) $value;
            }

            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return $value;
    }

    public function getIsJsonAttribute(): bool
    {
        if (!is_string($this->value)) {
            return false;
        }

        json_decode($this->value);

        return json_last_error() === JSON_ERROR_NONE;
    }

    public function getIsBooleanAttribute(): bool
    {
        return in_array($this->value, ['true', 'false'], true);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    /*
    |--------------------------------------------------------------------------
    | Static Methods - Cache-Aware Settings
    |--------------------------------------------------------------------------
    */

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting:{$key}", now()->addHours(6), function () use ($key, $default) {
            $setting = static::byKey($key)->first();

            if (!$setting) {
                return $default;
            }

            return $setting->typed_value;
        });
    }

    public static function set(string $key, mixed $value, ?string $group = null, ?string $description = null): Setting
    {
        $serializedValue = is_array($value) || is_object($value)
            ? json_encode($value)
            : (string) $value;

        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $serializedValue,
                'group' => $group ?? static::byKey($key)->value('group'),
                'description' => $description,
            ]
        );

        Cache::forget("setting:{$key}");

        return $setting;
    }

    public static function forget(string $key): bool
    {
        Cache::forget("setting:{$key}");

        return static::byKey($key)->delete() > 0;
    }

    public static function allGrouped(): array
    {
        return static::all()
            ->groupBy('group')
            ->map(fn ($items) => $items->keyBy('key'))
            ->toArray();
    }

    public static function getGroup(string $group): array
    {
        return static::byGroup($group)
            ->get()
            ->keyBy('key')
            ->map(fn (self $setting) => $setting->typed_value)
            ->toArray();
    }

    public static function clearCache(): void
    {
        $keys = static::pluck('key');
        $keys->each(fn (string $key) => Cache::forget("setting:{$key}"));
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function refreshCache(): void
    {
        Cache::forget("setting:{$this->key}");
    }
}
