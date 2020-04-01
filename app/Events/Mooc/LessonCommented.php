<?php

namespace App\Events\Mooc;

use App\Models\mooc\LessonComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LessonCommented
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LessonComment $comment)
    {
        $this->comment = $comment;
    }
}
