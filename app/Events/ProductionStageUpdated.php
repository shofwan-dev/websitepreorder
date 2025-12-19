<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductionStageUpdated
{
    use Dispatchable;

    public $order;
    public $stage;
    public $additionalInfo;

    public function __construct(Order $order, $stage, $additionalInfo = '')
    {
        $this->order = $order;
        $this->stage = $stage;
        $this->additionalInfo = $additionalInfo;
    }
}
