<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\SizesRequest;
use App\Http\Requests\UpdateSizesRequest;
use App\Models\Size;
use Illuminate\Http\Request;

class SizesControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = Size::with('category')->get();
        return Resp::Success('تم', $all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SizesRequest $request)
    {
        $size = new Size();

        $validate = (object) $request->validated();

        foreach ($validate as $key => $value) {
            $size->$key = $value;
        }

        try {
            $size->save();
            return Resp::Success('تمت الإضافة', $size);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function show(Size $size)
    {
        $size->load('category');
        return Resp::Success('تم', $size);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSizesRequest $request, Size $size)
    {
        $validate = (object) $request->validated();

        foreach ($validate as $key => $value) {
            $size->$key = $value;
        }

        try {
            $size->save();
            return Resp::Success('تم التحديث', $size);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy(Size $size)
    {
        $size->delete();
        return Resp::Success('تم الحذف', $size);
    }
}
