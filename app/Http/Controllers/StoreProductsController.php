<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Str;

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

    public function update(UpdateProductRequest $request, Product $product)
    {
        if (!$product->store->where('store_products.user_id', auth()->user()->id)->exists()) {
            return Resp::Error('لا تملك هذا المنتج', null);
        }


        $validate = $request->validated();

        foreach ($validate as $key => $value) {
            $product->$key = $value;
        }

        if (isset($request['photo'])) {
            $product->photo = Str::of($request->file('photo')->storePublicly('Product'));
        }

        try {

            $product->save();

            return Resp::Success('تم التحديث', $product);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }
}
