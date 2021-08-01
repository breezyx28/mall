<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductSizesControllerResource as pcr;

class ProductSizesController extends Controller
{
    public function sizesByProductID($id)
    {
        $pcr = new pcr();

        if ($pcr->checkProduct($id)) {
            return Resp::Error('انت لا تملك هذا المنتج');
        }

        $sizes = \App\Models\ProductSizes::with('product')->where('product_id', $id)->get();
        return Resp::Success('تم', $sizes);
    }
}
