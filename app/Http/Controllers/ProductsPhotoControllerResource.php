<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\ProductsPhotosRequest;
use App\Http\Requests\UpdateProductPhotosRequest;
use App\Models\ProductsPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductsPhotoControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = ProductsPhoto::with('product')->get();
        return Resp::Success('تم', $all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductsPhotosRequest $request)
    {
        $prodPho = new ProductsPhoto();
        $validate = (object) $request->validated();
        $error = false;
        $images = [];

        foreach ($request['photo'] as $key => $value) {

            $prodPho->photo = Str::of($request->file('photo.' . $key)->storePublicly('Product'));

            $prodPho->product_id = $validate->product_id;

            try {
                //code...
                $prodPho->save();
                array_push($images, $request->file('photo.' . $key)->getClientOriginalName());
                $prodPho = new ProductsPhoto();
            } catch (\Throwable $th) {
                Log::info('Issue :', ['problem' => $th->getMessage()]);
                $error = true;
                return 0;
            }
        }

        if ($error) {
            return Resp::Error('حدث خطأ اثناء إضافة الصور',  $images);
        } else {
            return Resp::Success('تمت إضافة الصور بنجاح', $images);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductsPhoto  $ProductsPhoto
     * @return \Illuminate\Http\Response
     */
    public function show(ProductsPhoto $productsPhoto)
    {
        return Resp::Success('تم', $productsPhoto);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductsPhoto  $ProductsPhoto
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductPhotosRequest $request, ProductsPhoto $ProductsPhoto)
    {
        $request->validated();

        $ProductsPhoto->photo = Str::of($request->file('photo')->storePublicly('Product'));

        try {
            $ProductsPhoto->save();
            return Resp::Success('تم التحديث بنجاح', $ProductsPhoto);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductsPhoto  $ProductsPhoto
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductsPhoto $productsPhoto)
    {
        $productsPhoto->delete();
        return Resp::Success('تم الحذف', $productsPhoto);
    }
}
