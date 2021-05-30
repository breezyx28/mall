<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\ProductSizesRequest;
use App\Http\Requests\UpdateProductSizeRequest;
use App\Models\ProductSizes;
use Illuminate\Http\Request;

class ProductSizesControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = ProductSizes::with('product')->get();
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

        foreach ($validate as $key => $value) {
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
        $productSize->delete();
        return Resp::Success('تم الحذف', $productSize);
    }
}
