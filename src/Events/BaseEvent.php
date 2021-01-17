<?php

namespace LaravelSferaLibrary\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function fire()
    {
        event($this);
    }
}
