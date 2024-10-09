<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('lease:check-expiry' )->monthlyOn(4, '09:00');
Schedule::command('transactions:process-recurring' )->dailyAt('01:00');
