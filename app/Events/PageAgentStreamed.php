<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class PageAgentStreamed implements ShouldBroadcastNow
{
    use Dispatchable;

    public function __construct(public string $room, public int $messageId, public string $text) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('page-chat.' . $this->room);
    }
}
