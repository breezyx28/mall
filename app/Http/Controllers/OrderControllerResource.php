<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderRequest;

class OrderControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('user', 'product', 'orderNumber', 'state')->get();
        return Resp::Success('تم', $orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $Order)
    {
        $data = $Order->load('user', 'product', 'orderNumber', 'state');
        // $orderNumberID = $Order->orders_number_id;
        // $orderNumber = \App\Models\OrdersNumber::find($orderNumberID)->orderNumber;
        // $invoice = \App\Models\Invoice::where('orderNumber', $orderNumber)->get();
        // return Resp::Success('تم', collect($data)->put('paymentMethod', $invoice[0]->payment_method));
        return Resp::Success('تم', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $Order)
    {
        $validate = (object) $request->validated();

        $Order->status = $validate->status;

        try {
            $Order->save();
            return Resp::Success('تم التحديث بنجاح', $Order);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $Order)
    {
        //
    }
}
