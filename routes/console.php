<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:daily-sales-report')->dailyAt('00:10');
