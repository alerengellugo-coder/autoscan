<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\EngineType;
use App\Models\Enums\TransmissionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'brand',
        'model',
        'year',
        'plate',
        'color',
        'vin',
        'mileage',
        'engine_type',
        'transmission',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'engine_type' => EngineType::class,
        'transmission' => TransmissionType::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model} {$this->year}";
    }

    public function getPlateFormattedAttribute(): string
    {
        return strtoupper($this->plate);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Activo',
            'in_service' => 'En servicio',
            'sold' => 'Vendido',
            'inactive' => 'Inactivo',
            default => ucfirst($this->status),
        };
    }

    public function getEngineTypeLabelAttribute(): string
    {
        return $this->engine_type?->label() ?? 'N/A';
    }

    public function getTransmissionLabelAttribute(): string
    {
        return $this->transmission?->label() ?? 'N/A';
    }

    public function getMileageFormattedAttribute(): string
    {
        return number_format($this->mileage ?? 0, 0, ',', '.') . ' km';
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInService($query)
    {
        return $query->where('status', 'in_service');
    }

    public function scopeByBrand($query, string $brand)
    {
        return $query->where('brand', $brand);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(fn ($q) => $q
            ->where('brand', 'like', "%{$term}%")
            ->orWhere('model', 'like', "%{$term}%")
            ->orWhere('plate', 'like', "%{$term}%")
            ->orWhere('vin', 'like', "%{$term}%")
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isAvailable(): bool
    {
        return $this->status === 'active';
    }

    public function isInService(): bool
    {
        return $this->status === 'in_service';
    }
}
