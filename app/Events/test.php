<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class test implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public mixed $mess;
    /**
     * Create a new event instance.
     */
    public function __construct( $mess ) {
        $this->mess = $mess;
    }
    /**
     */
    public function broadcastOn(): array {
        return [ 'aaa' ];
    }
    public function broadcastAs():string {
        return 'kak';
    }
}
