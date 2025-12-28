<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Order\GetDailySalesReport as GetDailySalesReportAction;
use App\Mail\DailySalesReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

final class SendDailySalesReport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public ?Carbon $date = null) {}

    /**
     * Execute the job.
     */
    public function handle(GetDailySalesReportAction $action): void
    {
        $report = $action->handle($this->date);

        $admins = User::where('is_admin', true)->get();

        if ($admins->isEmpty()) {
            $admins = collect([config('shop.admin_email')]);
        }

        foreach ($admins as $admin) {
            $email = $admin instanceof User ? $admin->email : $admin;
            Mail::to($email)->send(new DailySalesReport($report));
        }
    }
}
