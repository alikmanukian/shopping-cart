<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CartFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Cart extends Model
{
    /** @use HasFactory<CartFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    /**
     * @var list<string>
     */
    protected $appends = [
        'subtotal',
        'formatted_subtotal',
        'items_count',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<CartItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return Attribute<string, never>
     */
    protected function subtotal(): Attribute
    {
        return Attribute::get(function (): string {
            return number_format(
                (float) $this->items->sum(fn (CartItem $item): float => (float) $item->subtotal),
                2,
                '.',
                ''
            );
        });
    }

    /**
     * @return Attribute<string, never>
     */
    protected function formattedSubtotal(): Attribute
    {
        return Attribute::get(
            fn (): string => config('shop.currency_symbol').$this->subtotal
        );
    }

    /**
     * @return Attribute<int, never>
     */
    protected function itemsCount(): Attribute
    {
        return Attribute::get(
            fn (): int => (int) $this->items->sum('quantity')
        );
    }
}
