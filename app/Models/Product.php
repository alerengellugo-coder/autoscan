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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'category',
        'price',
        'cost',
        'stock',
        'min_stock',
        'unit',
        'is_active',
        'barcode',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'category' => ProductCategory::class,
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (self $product) {
            if (empty($product->slug)) {
                $product->slug = self::generateUniqueSlug($product->name);
            }
        });

        static::updating(function (self $product) {
            if ($product->isDirty('name') && $product->slug === Str::slug($product->getOriginal('name'))) {
                $product->slug = self::generateUniqueSlug($product->name, $product->id);
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

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

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
        if (!$this->price || $this->price == 0) {
            return 0.0;
        }

        $cost = (float) ($this->cost ?? 0);
        $price = (float) $this->price;

        return round((($price - $cost) / $price) * 100, 2);
    }

    public function getProfitAmountAttribute(): float
    {
        return round((float) $this->price - (float) ($this->cost ?? 0), 2);
    }

    public function getCategoryLabelAttribute(): string
    {
        return $this->category?->label() ?? 'N/A';
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'Agotado';
        }

        if ($this->stock <= $this->min_stock) {
            return 'Stock bajo';
        }

        return 'Disponible';
    }

    public function getStockStatusColorAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'danger';
        }

        if ($this->stock <= $this->min_stock) {
            return 'warning';
        }

        return 'success';
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
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
            ->orWhere('barcode', 'like', "%{$term}%")
            ->orWhere('description', 'like', "%{$term}%")
        );
    }

    public function scopeOrderByPrice($query, string $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }

    public function scopeOrderByStock($query, string $direction = 'asc')
    {
        return $query->orderBy('stock', $direction);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->min_stock;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public function decrementStock(int $quantity): bool
    {
        if ($this->stock < $quantity) {
            return false;
        }

        $this->decrement('stock', $quantity);
        return true;
    }

    public function incrementStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }
}
