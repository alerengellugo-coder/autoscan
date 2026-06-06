<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\QuotationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Quotation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quotation_number',
        'client_id',
        'vehicle_id',
        'technician_id',
        'service_order_id',
        'description',
        'status',
        'subtotal',
        'tax_rate',
        'tax',
        'discount',
        'discount_type',
        'total',
        'valid_until',
        'approved_at',
        'rejected_at',
        'notes',
        'terms_and_conditions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => QuotationStatus::class,
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:3',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'valid_until' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (self $quotation) {
            if (empty($quotation->quotation_number)) {
                $quotation->quotation_number = self::generateQuotationNumber();
            }
        });
    }

    protected static function generateQuotationNumber(): string
    {
        $prefix = 'COT';
        $date = now()->format('Ymd');
        $lastQuotation = self::where('quotation_number', 'like', "{$prefix}-{$date}-%")
            ->orderByDesc('id')
            ->value('quotation_number');

        if ($lastQuotation) {
            $lastSequence = (int) Str::afterLast($lastQuotation, '-');
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

    public function getFormattedSubtotalAttribute(): string
    {
        return '$ ' . number_format((float) $this->subtotal, 2, ',', '.');
    }

    public function getFormattedTaxAttribute(): string
    {
        return '$ ' . number_format((float) ($this->tax ?? 0), 2, ',', '.');
    }

    public function getFormattedDiscountAttribute(): string
    {
        return '$ ' . number_format((float) ($this->discount ?? 0), 2, ',', '.');
    }

    public function getFormattedTotalAttribute(): string
    {
        return '$ ' . number_format((float) $this->total, 2, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status?->label() ?? 'N/A';
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->valid_until) {
            return false;
        }

        return $this->valid_until->isPast()
            && in_array($this->status, [QuotationStatus::Draft, QuotationStatus::PendingClient]);
    }

    public function getItemCountAttribute(): int
    {
        return $this->items->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByStatus($query, QuotationStatus|string $status)
    {
        $value = $status instanceof QuotationStatus ? $status->value : $status;
        return $query->where('status', $value);
    }

    public function scopeDraft($query)
    {
        return $query->byStatus(QuotationStatus::Draft);
    }

    public function scopePending($query)
    {
        return $query->byStatus(QuotationStatus::PendingClient);
    }

    public function scopeApproved($query)
    {
        return $query->byStatus(QuotationStatus::Approved);
    }

    public function scopeExpired($query)
    {
        return $query->byStatus(QuotationStatus::Expired);
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
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

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    public function calculateTotals(): self
    {
        $subtotal = $this->items->sum(fn (QuotationItem $item) => (float) $item->total);
        $taxRate = (float) ($this->tax_rate ?? 0);
        $discount = (float) ($this->discount ?? 0);

        $taxableAmount = $subtotal - $discount;
        $tax = $taxableAmount * ($taxRate / 100);
        $total = $taxableAmount + $tax;

        $this->subtotal = $subtotal;
        $this->tax = round($tax, 2);
        $this->total = round($total, 2);

        $this->save();

        return $this;
    }

    public function approve(): bool
    {
        if (!$this->status->isEditable()) {
            return false;
        }

        $this->update([
            'status' => QuotationStatus::Approved,
            'approved_at' => now(),
        ]);

        return true;
    }

    public function reject(): bool
    {
        if (!$this->status->isEditable()) {
            return false;
        }

        $this->update([
            'status' => QuotationStatus::Rejected,
            'rejected_at' => now(),
        ]);

        return true;
    }

    public function markAsExpired(): bool
    {
        $this->update([
            'status' => QuotationStatus::Expired,
        ]);

        return true;
    }

    public function sendToClient(): bool
    {
        if ($this->status !== QuotationStatus::Draft) {
            return false;
        }

        $this->update([
            'status' => QuotationStatus::PendingClient,
        ]);

        return true;
    }
}
