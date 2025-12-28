<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\SendDailySalesReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;

final class SendDailySalesReportCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:daily-sales-report {--date= : The date for the report (Y-m-d format, defaults to yesterday)}';

    /**
     * @var string
     */
    protected $description = 'Send the daily sales report email to admin users';

    public function handle(): int
    {
        $date = $this->option('date')
            ? Date::parse($this->option('date'))
            : Date::yesterday();

        $this->info('Dispatching daily sales report...');

        dispatch_sync(new SendDailySalesReport($date));

        $this->info('Daily sales report sent successfully!');

        return self::SUCCESS;
    }
}
