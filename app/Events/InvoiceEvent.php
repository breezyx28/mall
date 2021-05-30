<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invoiceNumber, $orderNumber, $totalPrice, $accountNumber, $actualTotalPrice, $payment_method;

    public function __construct($invoiceNumber, $orderNumber, $totalPrice, $accountNumber, $actualTotalPrice, $payment_method)
    {
        $this->invoiceNumber = $invoiceNumber;
        $this->orderNumber = $orderNumber;
        $this->totalPrice = $totalPrice;
        $this->accountNumber = $accountNumber;
        $this->actualTotalPrice = $actualTotalPrice;
        $this->payment_method = $payment_method;
    }
}
