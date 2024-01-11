<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class sendTokenLogin implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public mixed $user;
    public mixed $token;
    /**
     * Create a new event instance.
     */
    public function __construct( $user, $token ) {
        $this->user = $user;
        $this->token = $token;
    }
    /**
     */
    public function broadcastOn(): Channel {
        return new Channel( 'sendTokenLogin' );
    }
    public function broadcastAs(): string {
        return 'private_msg';
    }
    public function broadcastWith(): array {
        return [
            "user" => $this->user,
            "token" => $this->token,
        ];
    }
}
