<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

final class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'total',
        'completed_at',
    ];

    public static function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-'.mb_strtoupper(Str::random(8));
        } while (self::query()->where('order_number', $number)->exists());

        return $number;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }

    public function markAsCancelled(): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);
    }

    protected static function booted(): void
    {
        self::creating(function (Order $order): void {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    /**
     * @param  Builder<Order>  $query
     * @return Builder<Order>
     */
    #[Scope]
    protected function completed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * @param  Builder<Order>  $query
     * @return Builder<Order>
     */
    #[Scope]
    protected function pending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return Attribute<non-empty-string, never>
     */
    protected function formattedTotal(): Attribute
    {
        return Attribute::get(
            fn (): string => moneyFormat($this->total)
        );
    }

    /**
     * @return Attribute<non-empty-string, never>
     */
    protected function formattedSubtotal(): Attribute
    {
        return Attribute::get(
            fn (): string => moneyFormat($this->subtotal)
        );
    }
}
