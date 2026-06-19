<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-publish event draft yang event_date-nya sudah tiba — jalan tiap hari jam 00:05
Schedule::command('events:auto-publish')->dailyAt('00:05');
