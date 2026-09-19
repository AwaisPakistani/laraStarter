<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SendWeeklyReport;
use App\Jobs\ImportExcelJob;
// 1. Schedule via Artisan command signature string
// Schedule::command('report:send')->dailyAt('08:00');

// 2. Schedule via Command Class Name (recommended)
// Schedule::command(SendWeeklyReport::class)->weeklyOn(1, '09:00');

// 3. Schedule an Inline Closure / Event directly
// Schedule::call(function () {
    // Perform custom task/event here
// })->everyFiveMinutes();

// 4. Schedule a Queued Job
// Schedule::job(new \App\Jobs\ImportExcelJob)->everyFive
// Minutes();
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
