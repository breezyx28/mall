<?php

namespace App\Http\Controllers;


use App\Helper\ResponseMessage as Resp;


use App\Models\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{

    public function myOrders(Request $request)
    {
        $validate = $request->validate([
            'status' => ['required', 'string|max:191', Rule::in(['available', 'accepted', 'rejected', 'delivered'])]
        ]);

        $orders = \App\Models\Order::with('product', 'state', 'orderNumber')
            ->where(['user_id', auth()->user()->id, 'status' => $validate['status']])
            ->get();

        return Resp::Success('تم', $orders);
    }

    public function orders()
    {
        $orders = \App\Models\Order::with('product', 'state', 'orderNumber')
            ->where(['user_id' => auth()->user()->id])->get();

        $data = collect($orders->groupBy('orderNumber.orderNumber')->all());

        return Resp::Success('تم', $data);
    }

    public function webOrders()
    {
        $orders = \App\Models\Order::with('product', 'state', 'orderNumber')
            ->where(['user_id' => auth()->user()->id])->get();

        $data = collect($orders->groupBy('orderNumber.orderNumber')->all());

        return Resp::Success('تم', collect($data)->sortDesc());
    }

    public function getMyOrder(Request $request)
    {
        $validate = (object) $request->validate([
            'orderNumber' => 'string|max:191'
        ]);

        try {
            $orders = \App\Models\OrdersNumber::where('orderNumber', $validate->orderNumber)->get();

            $ord = DB::table('orders');

            $res = $ord
                ->join("products", "orders.product_id", "products.id")
                ->join("users", "orders.user_id", "users.id")
                ->select(
                    "users.id as userID",
                    "users.firstName",
                    "users.middleName",
                    "users.lastName",
                    "orders.*",
                    "products.id as productID",
                    "products.name as productName",
                    "products.price as productPrice",
                    "products.discount as productDiscount",
                    "products.addetionalPrice as productAddetionalPrice",
                )
                ->selectRaw('(products.price - (products.price * (products.discount/100))) + products.addetionalPrice as final_price')
                ->where('orders_number_id', $orders[0]->id)->get();


            $invoiceInfo = \App\Models\Invoice::where('orderNumber', $validate->orderNumber)->get();

            return Resp::Success('ok', ["orderInfo" => $res, "userInfo" => auth()->user(), "invoiceInfo" => $invoiceInfo]);
        } catch (\Throwable $th) {
            //throw $th;
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    public function getMyOrderByNumber(Request $request)
    {
        $validate = (object) $request->validate([
            'orderNumber' => 'string|max:191'
        ]);

        try {
            $ordersNumbers = \App\Models\OrdersNumber::where('orderNumber', $validate->orderNumber)->get()->pluck('id');

            $data = \App\Models\Order::with('product')->whereIn('orders_number_id', $ordersNumbers)->get();

            return Resp::Success('ok', $data);
        } catch (\Throwable $th) {
            //throw $th;
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    public function storeOrders(Request $request)
    {
        $validate = (object) $request->validate([
            'orderNumber' => 'string|max:191'
        ]);

        try {
            $ordersNumbers = \App\Models\OrdersNumber::where('orderNumber', $validate->orderNumber)->get()->pluck('id');

            $data = \App\Models\Order::with('product:id,name,price,discount', 'user', 'state', 'orderNumber')->whereHas('product.store', function ($q) {
                $q->where('user_id', auth()->user()->id);
            })->whereIn('orders_number_id', $ordersNumbers)->get();

            $invoice = \App\Models\Invoice::where('orderNumber', $validate->orderNumber)->get();

            return Resp::Success('ok', $data->push(['payment_method' => $invoice[0]->payment_method]));
        } catch (\Throwable $th) {
            //throw $th;
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    public function adminOrders(Request $request)
    {
        $validate = (object) $request->validate([
            'orderNumber' => 'string|max:191'
        ]);

        try {
            // $ordersNumbers = \App\Models\OrdersNumber::where('orderNumber', $validate->orderNumber)->get()->pluck('id');

            $data = \App\Models\Order::with('product:id,name,price,discount', 'user', 'state', 'orderNumber')->whereHas('orderNumber', function ($q) use ($validate) {
                $q->where('orderNumber', $validate->orderNumber);
            })->get();

            $invoice = \App\Models\Invoice::where('orderNumber', $validate->orderNumber)->get();

            return Resp::Success('ok', $data->push(['payment_method' => $invoice[0]->payment_method]));
        } catch (\Throwable $th) {
            //throw $th;
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    public function ordersDetails()
    {

        $data =  \App\Models\OrdersNumber::with('order.product')->whereHas('order', function ($q) {
            $q->where('user_id', auth()->user()->id);
        })->get();

        $info = collect($data);

        $schema =  [
            'date' => '',
            'products' => '',
            'orderNumber' => '',
            'total' => '',
            'status' => '',
        ];

        $result = $info->map(function ($item, $key) use ($schema) {

            $schema['date'] = $item->updated_at;
            $schema['products'] = count($item->order);
            $schema['orderNumber'] = $item->orderNumber;
            $schema['total'] = collect($item->order)->sum('product.final_price');

            $schema['status'] = $item->order[0]->status;

            return $schema;
        });

        try {
            return Resp::Success('ok', $result);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    public function allStoreOrders()
    {
        $user = auth()->user();

        // get order Model
        $order = new Order();

        // get only my store product form order model
        $myProducts = $order::with('state:id,name,city', 'user:id,firstName,middleName,lastName,userName', 'product:id,name,price,discount,addetionalPrice', 'orderNumber')->whereHas('product.store', function ($q) {
            $q->where('user_id', auth()->user()->id);
        })->get();


        return Resp::Success('تم', $myProducts);
    }
}
