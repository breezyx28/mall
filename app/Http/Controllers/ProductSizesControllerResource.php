<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\ProductSizesRequest;
use App\Http\Requests\UpdateProductSizeRequest;
use App\Models\ProductSizes;
use Illuminate\Http\Request;

class ProductSizesControllerResource extends Controller
{
    private function checkProduct($product_id)
    {
        $get_store_id = \App\Models\Store::where('user_id', auth()->user()->id)->get()->all()[0]->id;
        $products_ids = \App\Models\StoreProduct::where('store_id', $get_store_id)->get()->pluck('id');

        if (in_array($product_id, $products_ids)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $get_store_id = \App\Models\Store::where('user_id', auth()->user()->id)->get()->all()[0]->id;

        $all = ProductSizes::with(['product', function ($q, $get_store_id) {
            $q->where('store_id', $get_store_id);
        }])->get();
        return Resp::Success('تم', $all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(ProductSizesRequest $request)
    {
        $validate = (object) $request->validated();

        $prodSizes = new \App\Models\ProductSizes();

        if ($this->checkProduct($validate->product_id)) {
            return Resp::Error('انت لا تملك هذا المنتج');
        }

        foreach ($validate as $key => $value) {
            $prodSizes->$key = $value;
        }

        try {
            $prodSizes->save();
            return Resp::Success('تم', $prodSizes);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductSizes  $productSizes
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSizes $productSize)
    {
        if ($this->checkProduct($productSize->product_id)) {
            return Resp::Error('انت لا تملك هذا المنتج');
        }

        $productSize->load('product');
        return Resp::Success('تم', $productSize);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductSizes  $productSizes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductSizeRequest $request, ProductSizes $productSize)
    {
        $validate = (object) $request->validated();

        if ($this->checkProduct($validate->product_id)) {
            return Resp::Error('انت لا تملك هذا المنتج');
        }

        foreach ($validate as $key => $value) {
            if ($validate->product_id)
                $productSize->$key = $value;
        }

        try {
            $productSize->save();
            return Resp::Success('تم التحديث', $productSize);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductSizes  $productSizes
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSizes $productSize)
    {
        if ($this->checkProduct($productSize->product_id)) {
            return Resp::Error('انت لا تملك هذا المنتج');
        }

        $productSize->delete();
        return Resp::Success('تم الحذف', $productSize);
    }
}
