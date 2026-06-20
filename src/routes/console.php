<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Jadwal otomatis:
 * - Reminder tagihan: setiap hari jam 08:00
 * - Mark overdue: setiap hari jam 00:01
 */
Schedule::command('kostify:send-reminders')->dailyAt('08:00');