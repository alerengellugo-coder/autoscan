<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quotation_id',
        'product_id',
        'item_type',
        'name',
        'description',
        'quantity',
        'unit_price',
        'discount',
        'discount_type',
        'total',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedUnitPriceAttribute(): string
    {
        return '$ ' . number_format((float) $this->unit_price, 2, ',', '.');
    }

    public function getFormattedDiscountAttribute(): string
    {
        return '$ ' . number_format((float) ($this->discount ?? 0), 2, ',', '.');
    }

    public function getFormattedTotalAttribute(): string
    {
        return '$ ' . number_format((float) $this->total, 2, ',', '.');
    }

    public function getEffectivePriceAttribute(): float
    {
        $discount = (float) ($this->discount ?? 0);
        $unitPrice = (float) $this->unit_price;

        if ($this->discount_type === 'percentage') {
            return round($unitPrice * (1 - $discount / 100), 2);
        }

        return round($unitPrice - $discount, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForQuotation($query, int $quotationId)
    {
        return $query->where('quotation_id', $quotationId);
    }

    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    public function recalculate(): self
    {
        $effectivePrice = $this->getEffectivePriceAttribute();
        $quantity = (float) $this->quantity;
        $total = round($effectivePrice * $quantity, 2);

        $this->total = $total;
        $this->save();

        return $this;
    }
}
