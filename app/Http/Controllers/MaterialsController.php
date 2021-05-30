<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialsController extends Controller
{
    public function getMaterialsBy(Request $request)
    {
        $validate = (object) $request->validate([
            'key' => 'required|in:id,materialName,category_id',
            'value' => 'string'
        ]);

        $material = new Material();

        if ($request->has(['key', 'value'])) {

            $data = $material::where($validate->key, $validate->value)->get();

            return Resp::Success('تم', $data);
        }

        if ($request->has('key')) {

            $data = $material::all()->groupBy($validate->key);

            return Resp::Success('تم', $data);
        }
    }
}
