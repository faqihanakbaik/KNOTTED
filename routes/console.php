<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\PaymentService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-cancel orders with expired payment deadline
Schedule::call(function () {
    $count = PaymentService::cancelExpiredOrders();
    
    if ($count > 0) {
        logger()->info("Auto-cancelled {$count} expired orders");
    }
})->hourly()->name('cancel-expired-orders')->withoutOverlapping();
