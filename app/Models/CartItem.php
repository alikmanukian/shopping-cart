<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CartItemFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CartItem extends Model
{
    /** @use HasFactory<CartItemFactory> */
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    /**
     * @var list<string>
     */
    protected $appends = [
        'subtotal',
        'formatted_subtotal',
    ];

    /**
     * @return BelongsTo<Cart, $this>
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    /**
     * @return Attribute<string, never>
     */
    protected function subtotal(): Attribute
    {
        return Attribute::get(function (): string {
            return number_format(
                (float) $this->product->price * $this->quantity,
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
}
