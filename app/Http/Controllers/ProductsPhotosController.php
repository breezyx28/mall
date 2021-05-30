<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\ResponseMessage as Resp;

class ProductsPhotosController extends Controller
{
    public function products()
    {
        return \App\Models\StoreProduct::where('user_id', auth()->user()->id)->pluck('product_id')->toArray();
    }

    public function storeProductPhotosByID($productsID)
    {
        if (!in_array($productsID, $this->products())) {
            return Resp::Error('لا تملك هذا المنتج', null);
        }

        return Resp::Success('تم', \App\Models\ProductsPhoto::where('product_id', $productsID)->get());
    }
}
