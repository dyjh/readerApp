<?php

namespace App\Listeners;

use ArrayObject;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnicodeNotEncodingOptions
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param RequestHandled $event
     * @return void
     */
    public function handle(RequestHandled $event)
    {
        $content = $event->response->original;
        if (empty($content)) {
            return;
        }

        if ($content instanceof Arrayable ||
            $content instanceof Jsonable ||
            $content instanceof ArrayObject ||
            $content instanceof \JsonSerializable ||
            is_array($content)) {
            $event->response->setContent(json_encode($content, \JSON_UNESCAPED_UNICODE));
        }
    }
}
