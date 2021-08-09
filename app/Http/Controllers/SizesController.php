<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizesController extends Controller
{
    public function getSizesBy(Request $request)
    {

        $validate = (object) $request->validate([
            'key' => 'required|in:id,unit,size,measureType,category_name',
            'value' => 'string'
        ]);

        $size = new Size();

        if ($request->has(['key', 'value'])) {

            $data = $size::where($validate->key, $validate->value)->get();

            return Resp::Success('تم', $data);
        }

        if ($request->has('key')) {

            $data = $size::all()->groupBy($validate->key);

            return Resp::Success('تم', $data);
        }
    }
}
