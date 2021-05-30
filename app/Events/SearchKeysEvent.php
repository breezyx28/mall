<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SearchKeysEvent
{
    public $key, $product_id;
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct($key, $product_id)
    {
        $this->$key = $key;
        $this->$product_id = $product_id;
    }
}
