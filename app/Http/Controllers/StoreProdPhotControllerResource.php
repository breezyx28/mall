<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\ProductsPhotosRequest;
use App\Http\Requests\UpdateProductPhotosRequest;
use App\Models\ProductsPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StoreProdPhotControllerResource extends Controller
{
    public function products()
    {
        return \App\Models\StoreProduct::where('user_id', auth()->user()->id)->pluck('product_id')->toArray();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = \App\Models\ProductsPhoto::whereIn('product_id', $this->products())->get();

        return Resp::Success('تم', $photos);
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

        if (!in_array($validate->product_id, $this->products())) {
            return Resp::Error('لا تملك هذا المنتج', null);
        }

        $error = false;
        $images = [];

        foreach ($request['photo'] as $key => $value) {

            $prodPho->photo = Str::of($request->file('photo.' . $key)->storePublicly('Product', 'spaces'));

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
     * @param  \App\Models\ProductsPhoto  $productsPhoto
     * @return \Illuminate\Http\Response
     */
    public function show(ProductsPhoto $storeProductPhoto)
    {
        if (!in_array($storeProductPhoto->product_id, $this->products())) {
            return Resp::Error('لا تملك هذا المنتج', null);
        }
        return Resp::Success('تم', $storeProductPhoto);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductsPhoto  $productsPhoto
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductPhotosRequest $request, ProductsPhoto $storeProductPhoto)
    {
        $validate = (object) $request->validated();

        if (!in_array($storeProductPhoto->product_id, $this->products())) {
            return Resp::Error('لا تملك هذا المنتج', null);
        }

        // remove old photo
        try {
            $remove = Str::replaceFirst('https://laravelstorage.sgp1.digitaloceanspaces.com/', '', $storeProductPhoto->photo);
            //code...
            Storage::disk('spaces')->delete($remove);
        } catch (\Throwable $th) {
            //throw $th;
            return Resp::Error('حدث خطأ أثناء حذف الصورة القديمة', $th->getMessage());
        }

        $storeProductPhoto->photo = Str::of($request->file('photo')->storePublicly('Product'));

        try {
            $storeProductPhoto->save();
            return Resp::Success('تم بنجاح', $storeProductPhoto);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductsPhoto  $productsPhoto
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductsPhoto $storeProductPhoto)
    {
        if (!in_array($storeProductPhoto->product_id, $this->products())) {
            return Resp::Error('لا تملك هذا المنتج', null);
        }

        $storeProductPhoto->delete();
        return Resp::Success('تم الحذف', $storeProductPhoto);
    }
}
