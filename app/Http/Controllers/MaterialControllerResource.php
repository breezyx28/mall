<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Http\Requests\MaterialsRequest;
use App\Http\Requests\UpdateMaterialsRequest;
use App\Helper\ResponseMessage as Resp;
use Illuminate\Http\Request;

class MaterialControllerResource extends Controller
{
    private function checkProduct($product_id)
    {
        $get_store_id = \App\Models\Store::where('user_id', auth()->user()->id)->get()->all()[0]->id;
        $products_ids = \App\Models\StoreProduct::where('store_id', $get_store_id)->get()->pluck('product_id')->values();

        if (in_array($product_id, (array) $products_ids)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $get_store_id = \App\Models\Store::where('user_id', auth()->user()->id)->get()->all()[0]->id;

        $all = Material::whereHas('product', function ($q) use ($get_store_id) {
            $q->where('store_id', $get_store_id);
        })->get();

        return Resp::Success('تم', $all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaterialsRequest $request)
    {
        $material = new Material();

        $validate = (object) $request->validated();

        if ($this->checkProduct($validate->product_id)) {
            return Resp::Error('انت لا تملك هذا المنتج');
        }

        foreach ($validate as $key => $value) {
            $material->$key = $value;
        }

        try {
            $material->save();
            return Resp::Success('تمت الإضافة', $material);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Material  $Material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $Material)
    {
        $mat = $Material->load('product');
        return Resp::Success('تم', $mat);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Material  $Material
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMaterialsRequest $request, Material $Material)
    {
        $validate = (object) $request->validated();

        if (isset($validate->product_id)) {
            if ($this->checkProduct($validate->product_id)) {
                return Resp::Error('انت لا تملك هذا المنتج');
            }
        }

        foreach ($validate as $key => $value) {
            $Material->$key = $value;
        }

        try {
            $Material->save();
            return Resp::Success('تم التحديث', $Material);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Material  $Material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $Material)
    {
        $Material->delete();
        return Resp::Success('تم الحذف', $Material);
    }
}
