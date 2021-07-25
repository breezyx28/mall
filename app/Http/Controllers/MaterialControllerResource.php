<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Http\Requests\MaterialsRequest;
use App\Http\Requests\UpdateMaterialsRequest;
use App\Helper\ResponseMessage as Resp;
use Illuminate\Http\Request;

class MaterialControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = Material::all();
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
        // $mat = $Material->load('category');
        return Resp::Success('تم', $Material);
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

        $Material->materialName = $validate->materialName;

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
