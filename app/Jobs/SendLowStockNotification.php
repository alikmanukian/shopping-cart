<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\LowStockAlert;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

final class SendLowStockNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Product $product) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $admins = User::query()->where('is_admin', true)->get();

        if ($admins->isEmpty()) {
            $admins = collect([config('shop.admin_email')]);
        }

        foreach ($admins as $admin) {
            $email = $admin instanceof User ? $admin->email : $admin;
            Mail::to($email)->send(new LowStockAlert($this->product));
        }
    }
}
