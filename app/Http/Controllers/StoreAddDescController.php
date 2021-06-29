<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreAddDescController extends Controller
{
    public function products()
    {
        return \App\Models\StoreProduct::where('user_id', auth()->user()->id)->pluck('product_id')->all();
    }

    public function storeAdditionalDescriptionBy($productID)
    {
        if (!in_array($productID, (array) $this->products())) {
            return Resp::Error('لا تملك هذا المنتج', null);
        }

        $result  = \App\Models\AdditionalDescription::with('product')->where('product_id', $productID)->get();

        return Resp::Success('تم', $result);
    }
}
