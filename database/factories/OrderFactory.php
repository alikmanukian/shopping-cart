<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
final class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 200);

        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-'.mb_strtoupper(Str::random(8)),
            'status' => Order::STATUS_PENDING,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'completed_at' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Order::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Order::STATUS_PROCESSING,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Order::STATUS_CANCELLED,
        ]);
    }
}
