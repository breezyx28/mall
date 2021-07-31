<?php

namespace App\Http\Controllers;

use App\Models\MaterialsList;
use App\Http\Controllers\Controller;
use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\MaterialsListRequest;
use App\Http\Requests\MaterialsRequest;
use App\Http\Requests\UpdateMaterialsListRequest;
use Illuminate\Http\Request;

class MaterialsListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = MaterialsList::all();
        return Resp::Success('تم', $all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaterialsListRequest $request)
    {
        $material = new MaterialsList();

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
     * @param  \App\Models\MaterialsList  $materialsList
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialsList $materialsList)
    {
        return Resp::Success('تم', $materialsList);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaterialsList  $materialsList
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMaterialsListRequest $request, MaterialsList $materialsList)
    {
        $validate = (object) $request->validated();

        $materialsList->name = $validate->name;

        try {
            $materialsList->save();
            return Resp::Success('تم التحديث', $materialsList);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialsList  $materialsList
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaterialsList $materialsList)
    {
        $materialsList->delete();
        return Resp::Success('تم الحذف', $materialsList);
    }
}
