<?php

namespace App\Events\shares;

use App\Models\shares\StudentsBooksRent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SharedBookReturnedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var StudentsBooksRent
     */
    private $studentsBooksRent;

    /**
     * Create a new event instance.
     *
     * @param StudentsBooksRent $studentsBooksRent
     */
    public function __construct(StudentsBooksRent $studentsBooksRent)
    {
        $this->studentsBooksRent = $studentsBooksRent;
    }

    /**
     * @return StudentsBooksRent
     */
    public function getStudentsBooksRent(): StudentsBooksRent
    {
        return $this->studentsBooksRent;
    }

}
