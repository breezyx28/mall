<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StoreProductEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product, $storeID;

    public function __construct($product, $storeID)
    {
        $this->storeID = $storeID;
        // $this->productID = $productID;
        $this->product = $product;
    }
}
