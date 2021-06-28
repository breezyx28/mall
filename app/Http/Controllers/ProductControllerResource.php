<?php

namespace App\Http\Controllers;

use App\Helper\AuthUser;
use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\ProductsRequest;
use App\Http\Requests\UpdateProductAdmin;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prod = \App\Models\Product::with('category', 'store.store', 'rate', 'product_photos', 'additional_description', 'product_sizes')->get();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $Product)
    {
        $Product->load('category', 'store.store', 'rate', 'product_photos', 'additional_description', 'product_sizes');
        return Resp::Success('تم', $Product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductAdmin $request, Product $Product)
    {
        $validate = (object) $request->validated();

        $prod = new \App\Models\Product();

        if (auth()->user()->role_id == 1) {

            if (!isset($validate->price)) {
                return Resp::Error('السعر مطلوب', 'السعر مطلوب');
            }
            $Product->price = $validate->price;

            try {
                $Product->save();
                return Resp::Success('تم تحديث البيانات بنجاح', $Product);
            } catch (\Throwable $th) {
                return Resp::Error('حدث خطأ ما', $th->getMessage());
            }
            return 0;
        }

        foreach ($validate as $key => $value) {
            if ($validate->$key == 'photo') {
                $prod->photo = Str::of($request->file('photo')->storePublicly('Product'));
            }
            $prod->$key = $value;
        }


        try {
            $prod->save();
            return Resp::Success('تم', $prod);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $Product)
    {
        $prod = $Product->delete();
        return Resp::Success('تم الحذف', $prod);
    }
}
