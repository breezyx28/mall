<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductSizesController extends Controller
{
    public function sizesByProductID($id)
    {

        $sizes = \App\Models\ProductSizes::with('product')->where('product_id', $id)->get();
        return Resp::Success('تم', $sizes);
    }
}
