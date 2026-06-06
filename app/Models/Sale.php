<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\PaymentMethod;
use App\Models\Enums\SaleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Sale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_number',
        'client_id',
        'quotation_id',
        'description',
        'status',
        'subtotal',
        'tax_rate',
        'tax',
        'discount',
        'discount_type',
        'total',
        'paid_amount',
        'payment_method',
        'paid_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => SaleStatus::class,
        'payment_method' => PaymentMethod::class,
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:3',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (self $sale) {
            if (empty($sale->sale_number)) {
                $sale->sale_number = self::generateSaleNumber();
            }
        });
    }

    protected static function generateSaleNumber(): string
    {
        $prefix = 'VTA';
        $date = now()->format('Ymd');
        $lastSale = self::where('sale_number', 'like', "{$prefix}-{$date}-%")
            ->orderByDesc('id')
            ->value('sale_number');

        if ($lastSale) {
            $lastSequence = (int) Str::afterLast($lastSale, '-');
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

    public function getFormattedPaidAmountAttribute(): string
    {
        return '$ ' . number_format((float) ($this->paid_amount ?? 0), 2, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status?->label() ?? 'N/A';
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return $this->payment_method?->label() ?? 'N/A';
    }

    public function getRemainingAmountAttribute(): float
    {
        return round((float) $this->total - (float) ($this->paid_amount ?? 0), 2);
    }

    public function getFormattedRemainingAmountAttribute(): string
    {
        return '$ ' . number_format($this->remaining_amount, 2, ',', '.');
    }

    public function getIsFullyPaidAttribute(): bool
    {
        return (float) ($this->paid_amount ?? 0) >= (float) $this->total;
    }

    public function getChangeAmountAttribute(): float
    {
        $paid = (float) ($this->paid_amount ?? 0);
        $total = (float) $this->total;

        if ($paid > $total) {
            return round($paid - $total, 2);
        }

        return 0.0;
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

    public function scopeByStatus($query, SaleStatus|string $status)
    {
        $value = $status instanceof SaleStatus ? $status->value : $status;
        return $query->where('status', $value);
    }

    public function scopePaid($query)
    {
        return $query->byStatus(SaleStatus::Paid);
    }

    public function scopePending($query)
    {
        return $query->byStatus(SaleStatus::Pending);
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeByPaymentMethod($query, PaymentMethod|string $method)
    {
        $value = $method instanceof PaymentMethod ? $method->value : $method;
        return $query->where('payment_method', $value);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeByPaidDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('paid_at', [$startDate, $endDate]);
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

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    public function calculateTotals(): self
    {
        $subtotal = $this->items->sum(fn (SaleItem $item) => (float) $item->total);
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

    public function registerPayment(float $amount, ?PaymentMethod $method = null): bool
    {
        if ($this->status === SaleStatus::Cancelled) {
            return false;
        }

        $paidAmount = (float) ($this->paid_amount ?? 0) + $amount;

        if ($method) {
            $this->payment_method = $method;
        }

        $this->paid_amount = round($paidAmount, 2);

        if ($paidAmount >= (float) $this->total) {
            $this->status = SaleStatus::Paid;
            $this->paid_at = now();
        } else {
            $this->status = SaleStatus::PartiallyPaid;
        }

        $this->save();

        return true;
    }

    public function cancel(): bool
    {
        if (in_array($this->status, [SaleStatus::Cancelled])) {
            return false;
        }

        $this->update([
            'status' => SaleStatus::Cancelled,
        ]);

        // Restore product stock
        $this->items->each(function (SaleItem $item) {
            if ($item->product) {
                $item->product->incrementStock((int) $item->quantity);
            }
        });

        return true;
    }

    public function markAsPaid(): bool
    {
        $this->update([
            'status' => SaleStatus::Paid,
            'paid_amount' => $this->total,
            'paid_at' => now(),
        ]);

        return true;
    }
}
