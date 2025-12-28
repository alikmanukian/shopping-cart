<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $appends = [
        'image_url',
        'formatted_price',
        'is_low_stock',
        'is_in_stock',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock_quantity',
        'image',
        'is_active',
    ];

    /**
     * @return HasMany<CartItem, $this>
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        self::creating(function (Product $product): void {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return Attribute<string, never>
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::get(
            fn (): string => config('shop.currency_symbol').$this->price
        );
    }

    /**
     * @return Attribute<bool, never>
     */
    protected function isLowStock(): Attribute
    {
        return Attribute::get(
            fn (): bool => $this->stock_quantity <= config('shop.low_stock_threshold')
        );
    }

    /**
     * @return Attribute<bool, never>
     */
    protected function isInStock(): Attribute
    {
        return Attribute::get(
            fn (): bool => $this->stock_quantity > 0
        );
    }

    /**
     * @return Attribute<string|null, never>
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            $image = $this->attributes['image'] ?? null;
            if (! $image) {
                return null;
            }

            // If already a full URL or absolute path, return as-is
            if (str_starts_with($image, '/') || str_starts_with($image, 'http')) {
                return $image;
            }

            // Otherwise, assume it's a storage path
            return Storage::url($image);
        });
    }
}
