<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\ProductCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'category',
        'brand',
        'price',
        'cost',
        'stock_quantity',
        'min_stock_alert',
        'unit',
        'image_path',
        'is_active',
        'is_service',
    ];

    protected $casts = [
        'category' => ProductCategory::class,
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_stock_alert' => 'integer',
        'is_active' => 'boolean',
        'is_service' => 'boolean',
    ];

    protected $appends = ['stock', 'min_stock', 'formatted_price', 'formatted_cost', 'stock_status', 'stock_status_color'];

    // Virtual attributes for compatibility with views
    public function getStockAttribute(): int
    {
        return $this->attributes['stock_quantity'] ?? 0;
    }

    public function getMinStockAttribute(): int
    {
        return $this->attributes['min_stock_alert'] ?? 0;
    }

    protected static function booted(): void
    {
        static::creating(function (self $product) {
            if (empty($product->slug)) {
                $product->slug = self::generateUniqueSlug($product->name);
            }
        });
    }

    protected static function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = self::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            if (!$query->exists()) {
                break;
            }
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }
        return $slug;
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$ ' . number_format((float) $this->price, 2, ',', '.');
    }

    public function getFormattedCostAttribute(): string
    {
        return '$ ' . number_format((float) ($this->cost ?? 0), 2, ',', '.');
    }

    public function getProfitMarginAttribute(): float
    {
        if (!$this->price || $this->price == 0) return 0.0;
        return round((($this->price - ($this->cost ?? 0)) / $this->price) * 100, 2);
    }

    public function getProfitAmountAttribute(): float
    {
        return round((float) $this->price - (float) ($this->cost ?? 0), 2);
    }

    public function getStockStatusAttribute(): string
    {
        $stock = $this->stock_quantity;
        $min = $this->min_stock_alert;
        if ($stock <= 0) return 'Agotado';
        if ($stock <= $min) return 'Stock bajo';
        return 'Disponible';
    }

    public function getStockStatusColorAttribute(): string
    {
        $stock = $this->stock_quantity;
        $min = $this->min_stock_alert;
        if ($stock <= 0) return 'danger';
        if ($stock <= $min) return 'warning';
        return 'success';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock_alert');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    public function scopeCategory($query, ProductCategory|string $category)
    {
        $value = $category instanceof ProductCategory ? $category->value : $category;
        return $query->where('category', $value);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(fn ($q) => $q
            ->where('name', 'like', "%{$term}%")
            ->orWhere('sku', 'like', "%{$term}%")
            ->orWhere('description', 'like', "%{$term}%")
        );
    }

    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock_quantity > 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity > 0 && $this->stock_quantity <= $this->min_stock_alert;
    }

    public function decrementStock(int $quantity): bool
    {
        if ($this->stock_quantity < $quantity) return false;
        $this->decrement('stock_quantity', $quantity);
        return true;
    }

    public function incrementStock(int $quantity): void
    {
        $this->increment('stock_quantity', $quantity);
    }
}
