<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceReport extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_order_id',
        'technician_id',
        'report_date',
        'title',
        'description',
        'work_performed',
        'previous_status',
        'new_status',
        'labor_hours',
        'parts_used',
        'findings',
        'recommendations',
        'images',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'report_date' => 'date',
        'labor_hours' => 'decimal:2',
        'parts_used' => 'array',
        'images' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedLaborHoursAttribute(): string
    {
        $hours = (float) ($this->labor_hours ?? 0);
        $wholeHours = (int) floor($hours);
        $minutes = (int) round(($hours - $wholeHours) * 60);

        if ($wholeHours > 0 && $minutes > 0) {
            return "{$wholeHours}h {$minutes}m";
        }

        if ($wholeHours > 0) {
            return "{$wholeHours}h";
        }

        return "{$minutes}m";
    }

    public function getPartsCountAttribute(): int
    {
        return is_array($this->parts_used) ? count($this->parts_used) : 0;
    }

    public function getImagesCountAttribute(): int
    {
        return is_array($this->images) ? count($this->images) : 0;
    }

    public function getPartsSummaryAttribute(): string
    {
        if (!is_array($this->parts_used) || empty($this->parts_used)) {
            return 'Sin repuestos registrados';
        }

        return collect($this->parts_used)
            ->map(fn (array $part) => $part['name'] ?? 'Sin nombre')
            ->join(', ');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForOrder($query, int $orderId)
    {
        return $query->where('service_order_id', $orderId);
    }

    public function scopeByTechnician($query, int $technicianId)
    {
        return $query->where('technician_id', $technicianId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('report_date', [$startDate, $endDate]);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('report_date');
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function hasParts(): bool
    {
        return is_array($this->parts_used) && count($this->parts_used) > 0;
    }

    public function hasImages(): bool
    {
        return is_array($this->images) && count($this->images) > 0;
    }
}
