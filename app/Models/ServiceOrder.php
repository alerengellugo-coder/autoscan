<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\OrderPriority;
use App\Models\Enums\OrderStatus;
use App\Models\Enums\ServiceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class ServiceOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'vehicle_id',
        'client_id',
        'technician_id',
        'service_type',
        'description',
        'diagnosis',
        'status',
        'priority',
        'estimated_cost',
        'actual_cost',
        'estimated_completion_date',
        'started_at',
        'completed_at',
        'delivered_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => OrderStatus::class,
        'priority' => OrderPriority::class,
        'service_type' => ServiceType::class,
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'estimated_completion_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (self $order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    protected static function generateOrderNumber(): string
    {
        $prefix = 'OS';
        $date = now()->format('Ymd');
        $lastOrder = self::where('order_number', 'like', "{$prefix}-{$date}-%")
            ->orderByDesc('id')
            ->value('order_number');

        if ($lastOrder) {
            $lastSequence = (int) Str::afterLast($lastOrder, '-');
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $date, $nextSequence);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedEstimatedCostAttribute(): string
    {
        return '$ ' . number_format((float) $this->estimated_cost, 2, ',', '.');
    }

    public function getFormattedActualCostAttribute(): string
    {
        return '$ ' . number_format((float) ($this->actual_cost ?? 0), 2, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status?->label() ?? 'N/A';
    }

    public function getPriorityLabelAttribute(): string
    {
        return $this->priority?->label() ?? 'N/A';
    }

    public function getServiceTypeLabelAttribute(): string
    {
        return $this->service_type?->label() ?? 'N/A';
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->estimated_completion_date) {
            return false;
        }

        return $this->estimated_completion_date->isPast()
            && !$this->status?->isFinal();
    }

    public function getDurationDaysAttribute(): ?int
    {
        if (!$this->started_at) {
            return null;
        }

        $end = $this->completed_at ?? now();
        return $this->started_at->diffInDays($end);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByStatus($query, OrderStatus|string $status)
    {
        $value = $status instanceof OrderStatus ? $status->value : $status;
        return $query->where('status', $value);
    }

    public function scopeByPriority($query, OrderPriority|string $priority)
    {
        $value = $priority instanceof OrderPriority ? $priority->value : $priority;
        return $query->where('priority', $value);
    }

    public function scopePending($query)
    {
        return $query->byStatus(OrderStatus::Pending);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            OrderStatus::Pending->value,
            OrderStatus::Diagnosing->value,
            OrderStatus::InProgress->value,
            OrderStatus::WaitingParts->value,
            OrderStatus::QualityCheck->value,
        ]);
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', [
            OrderStatus::Completed->value,
            OrderStatus::Delivered->value,
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->active()
            ->where('estimated_completion_date', '<', now()->toDateString());
    }

    public function scopeForTechnician($query, int $technicianId)
    {
        return $query->where('technician_id', $technicianId);
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeOrderByPriority($query, string $direction = 'asc')
    {
        $priorityOrder = [
            OrderPriority::Urgent->value,
            OrderPriority::High->value,
            OrderPriority::Normal->value,
            OrderPriority::Low->value,
        ];

        return $query->orderByRaw(
            'FIELD(priority, ' . implode(',', array_map(fn ($p) => "'{$p}'", $priorityOrder)) . ') ' . $direction
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ServiceReport::class);
    }

    public function quotation(): HasOne
    {
        return $this->hasOne(Quotation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function canTransitionTo(OrderStatus $newStatus): bool
    {
        $transitions = [
            OrderStatus::Pending->value => [OrderStatus::Diagnosing, OrderStatus::Cancelled],
            OrderStatus::Diagnosing->value => [OrderStatus::InProgress, OrderStatus::WaitingParts, OrderStatus::Cancelled],
            OrderStatus::InProgress->value => [OrderStatus::QualityCheck, OrderStatus::WaitingParts, OrderStatus::Cancelled],
            OrderStatus::WaitingParts->value => [OrderStatus::InProgress, OrderStatus::Cancelled],
            OrderStatus::QualityCheck->value => [OrderStatus::Completed, OrderStatus::InProgress],
            OrderStatus::Completed->value => [OrderStatus::Delivered],
            OrderStatus::Delivered->value => [],
            OrderStatus::Cancelled->value => [],
        ];

        $allowed = $transitions[$this->status?->value ?? OrderStatus::Pending->value] ?? [];

        return in_array($newStatus, $allowed, strict: true);
    }
}
