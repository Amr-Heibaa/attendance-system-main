<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ✅ Daily close at 20:00
app(Schedule::class)
    ->command('attendance:close-daily')
    ->dailyAt('20:00');