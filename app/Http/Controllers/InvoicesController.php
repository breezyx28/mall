<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    public function myInvoices()
    {

        $user = auth()->user();

        $invoices = \App\Models\Invoice::with('account')->where('user_id', $user->id)->get();

        return Resp::Success('done', $invoices);
    }

    public function storeInvoices()
    {
        // get order Model
        $order = new Order();

        // get only my store product form order model
        $myProducts = $order::with('state:id,name,city', 'user:id,firstName,middleName,lastName,userName', 'product:id,name,price,discount,addetionalPrice', 'orderNumber')->whereHas('product.store', function ($q) {
            $q->where('user_id', auth()->user()->id);
        })->get(['id', 'product_id', 'user_id', 'state_id', 'amount', 'orders_number_id', 'order_address', 'status', 'created_at', 'updated_at']);


        return Resp::Success('done', $myProducts);
    }
}
