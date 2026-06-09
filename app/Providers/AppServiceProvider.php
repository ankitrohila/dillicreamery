<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\CartService::class);
        $this->app->singleton(\App\Services\PaymentService::class);
        $this->app->singleton(\App\Services\InventoryService::class);
        $this->app->singleton(\App\Services\CouponService::class);
        $this->app->singleton(\App\Services\OrderService::class);
        $this->app->singleton(\App\Services\SubscriptionService::class);
        $this->app->singleton(\App\Services\WhatsAppService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS when behind Railway's reverse proxy
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Order placed → send WhatsApp confirmation + invoice
        Event::listen(
            \App\Events\OrderPlaced::class,
            \App\Listeners\SendOrderWhatsApp::class,
        );

        // Order status changed → send WhatsApp update
        Event::listen(
            \App\Events\OrderStatusChanged::class,
            \App\Listeners\SendOrderStatusWhatsApp::class,
        );

        // Subscription created → send WhatsApp welcome
        Event::listen(
            \App\Events\SubscriptionCreated::class,
            \App\Listeners\SendSubscriptionWhatsApp::class,
        );
    }
}
