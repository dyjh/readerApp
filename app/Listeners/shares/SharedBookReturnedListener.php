<?php

namespace App\Listeners\shares;

use App\Events\shares\SharedBookReturnedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SharedBookReturnedListener
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SharedBookReturnedEvent $event)
    {
        $bookRent = $event->getStudentsBooksRent();

        // 书籍借阅
        $bookRent->renter->increment('read_count');
    }
}
