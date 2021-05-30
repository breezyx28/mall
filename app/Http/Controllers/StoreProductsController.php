<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreProductsController extends Controller
{
    public function storeProducts(Request $request)
    {

        $validate = (object) $request->validate([
            'store_id' => 'required|integer|exists:stores,id'
        ]);

        try {
            //code...
            $products = \App\Models\StoreProduct::where('store_id', $validate->store_id)->pluck('product_id');
            $data = \App\Models\Product::find($products);

            return Resp::Success('تم', $data);
        } catch (\Throwable $th) {
            //throw $th;
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }
}
