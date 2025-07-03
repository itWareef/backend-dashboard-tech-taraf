<?php

namespace App\Events;

use App\Models\GpsLocation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class GpsLocationUpdated implements ShouldBroadcastNow
{
    use SerializesModels;

    public $location;

    public function __construct(GpsLocation $location)
    {
        $this->location = $location;
    }

    public function broadcastOn()
    {
        return new Channel('gps-tracking');
    }

    public function broadcastWith()
    {
        return [
            'data' => [
                $this->location
            ]
        ];
    }
    public function broadcastAs()
    {
        return 'location.updated';
    }
}
