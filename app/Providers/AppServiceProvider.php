<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
use App\Models\OrderItem;
use App\Observers\OrderObserver;
use App\Observers\OrderItemObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Kode lama kamu (tetap dipertahankan)
       Schema::defaultStringLength(191);

        // Registrasi Observers
        Order::observe(OrderObserver::class);
        OrderItem::observe(OrderItemObserver::class);
    }
}