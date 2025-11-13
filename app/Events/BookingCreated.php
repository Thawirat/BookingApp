<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class BookingCreated implements ShouldBroadcast
{
    use SerializesModels;

    public $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function broadcastOn()
    {
        return new Channel('bookings');
    }

    public function broadcastAs()
    {
        return 'new-booking';
    }
}
