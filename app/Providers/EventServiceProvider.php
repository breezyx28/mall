<?php

namespace App\Providers;

use App\Events\InvoiceEvent;
use App\Events\NotificationEvent;
use App\Events\SearchKeysEvent;
use App\Events\sendVerificationEvent;
use App\Events\StoreProductEvent;
use App\Http\Controllers\InvoiceControllerResource;
use App\Listeners\NotificationListener;
use App\Listeners\OrdersInvoiceListener;
use App\Listeners\SearchKeysListener;
use App\Listeners\SendVerificationListener;
use App\Listeners\StoreProductListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        StoreProductEvent::class => [
            StoreProductListener::class,
        ],
        InvoiceEvent::class => [
            OrdersInvoiceListener::class,
        ],
        NotificationEvent::class => [
            NotificationListener::class,
        ],
        sendVerificationEvent::class => [
            SendVerificationListener::class,
        ],
        SearchKeysEvent::class => [
            SearchKeysListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
