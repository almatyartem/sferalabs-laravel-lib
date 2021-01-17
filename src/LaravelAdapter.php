<?php

namespace LaravelSferaLibrary;

class LaravelAdapter
{
    public function fireEvent($event)
    {
        event($event);
    }
}
