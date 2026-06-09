<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'product_id',
        'item_type',
        'name',
        'description',
        'quantity',
        'unit_price',
        'cost',
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
        'cost' => 'decimal:2',
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

    public function getFormattedCostAttribute(): string
    {
        return '$ ' . number_format((float) ($this->cost ?? 0), 2, ',', '.');
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

    public function getProfitAttribute(): float
    {
        $effectivePrice = $this->getEffectivePriceAttribute();
        $cost = (float) ($this->cost ?? 0);
        $quantity = (float) $this->quantity;

        return round(($effectivePrice - $cost) * $quantity, 2);
    }

    public function getProfitMarginAttribute(): float
    {
        $unitPrice = (float) $this->unit_price;
        if ($unitPrice <= 0) {
            return 0.0;
        }

        $cost = (float) ($this->cost ?? 0);

        return round((($unitPrice - $cost) / $unitPrice) * 100, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForSale($query, int $saleId)
    {
        return $query->where('sale_id', $saleId);
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

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
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
