<?php

namespace App\Listeners;

use App\Events\InvoiceEvent;
use App\Helper\ResponseMessage as Resp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrdersInvoiceListener
{

    public function handle(InvoiceEvent $event)
    {
        $user = auth()->user();
        $invoice = new \App\Models\Invoice();

        // check if the user ownn the card number
        try {
            //code...
            $collection = \App\Models\Account::find($user->id);

            collect($collection)->contains(function ($value, $key) use (&$event) {
                $value == $event->accountNumber;
            });
        } catch (\Throwable $th) {
            //throw $th;
            Resp::Error('خطأ في invoicesListener', $th->getMessage());
            return false;
        }

        $invoice->invoiceNumber = $event->invoiceNumber;
        $invoice->orderNumber = $event->orderNumber;
        $invoice->discount = 100 - ((($event->totalPrice) / ($event->actualTotalPrice)) * 100);
        $invoice->total = $event->totalPrice;
        $invoice->user_id = $user->id;
        $invoice->account_id = $event->accountNumber;
        $invoice->payment_method = $event->payment_method;

        try {
            $invoice->save();
            return Resp::Success('done', $invoice);
        } catch (\Throwable $th) {
            //throw $th;
            return Resp::Error('error', $th->getMessage());
        }
    }
}
