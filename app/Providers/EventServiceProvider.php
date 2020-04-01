<?php

namespace App\Providers;

use App\Events\shares\SharedBookReturnedEvent;
use App\Listeners\Mooc\GlobalListener;
use App\Listeners\shares\SharedBookReturnedListener;
use App\Listeners\UnicodeNotEncodingOptions;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Order\OrderItemCommented' => [
            'App\Listeners\Order\UpdateProductRating',
        ],
        'App\Events\Mooc\LessonCommented'     => [
            'App\Listeners\Mooc\UpdateLessonRating',
        ],
        SharedBookReturnedEvent::class => [
            SharedBookReturnedListener::class
        ],
        'App\Events\Order\OrderCancel' => [
            'App\Listeners\Order\UpdateProductStock',
        ],

        // unset json encoding
//        RequestHandled::class => [
//            UnicodeNotEncodingOptions::class
//        ]
    ];

    protected $subscribe = [
        GlobalListener::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
