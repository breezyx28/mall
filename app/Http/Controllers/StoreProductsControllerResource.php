<?php

namespace App\Http\Controllers;

use App\Events\StoreProductEvent;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helper\ResponseMessage as Resp;
use App\Helper\ValidateArray;
use App\Http\Requests\ProductsRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\StoreProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreProductsControllerResource extends Controller
{
    private $storeID;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prod = \App\Models\StoreProduct::with(['store', 'product', 'user' => function ($query) {
            $query->where('id', auth()->user()->id);
        }])->get();

        return Resp::Success('تم بنجاح', $prod);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductsRequest $request)
    {
        $validate = (object) $request->validated();

        $product = new \App\Models\Product();

        foreach ($validate as $key => $value) {

            if (isset($validate->store_id)) {
                $this->storeID = $validate->store_id;
            }

            // if (isset($validate->description)) {
            //     $validate->description = json_encode($validate->description);
            // }

            $product->$key = $value;
        }

        $product->photo = Str::of($request->file('photo')->storePublicly('Product'));

        return event(new StoreProductEvent($product, $this->storeID))[0]->original;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $storeProd = new StoreProduct();
        $data = $storeProd->with('product', 'store')->where(['product_id' => $product->id, 'user_id' => auth()->user()->id])->get();
        return Resp::Success('تم', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (!$product->store->where('store_products.user_id', auth()->user()->id)->exists()) {
            return Resp::Error('لا تملك هذا المنتج', null);
        }

        try {
            $product->delete();
            return Resp::Success('تم الحذف', $product);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }
}
