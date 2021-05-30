<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\AdditionalDescriptionsRequest;
use App\Http\Requests\UpdateAdditionalDescriptionRequest;
use App\Models\AdditionalDescription;
use Illuminate\Http\Request;

class AdditionalDescriptionControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = AdditionalDescription::with('product')->get();
        return Resp::Success('تم', $all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdditionalDescriptionsRequest $request)
    {
        $validate = (object) $request->validated();
        $addDesc =  new AdditionalDescription();

        foreach ($validate as $key => $value) {
            if ($validate->$key == 'expireDate') {

                $addDesc->expireDate = date('Y-m-d', strtotime($validate->expireDate));
            }

            $addDesc->$key = $value;
        }

        try {
            $addDesc->save();
            return Resp::Success('تم إنشاء المواصفات بنجاح', $addDesc);
        } catch (\Throwable $th) {
            //throw $th;
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdditionalDescription  $additionalDescription
     * @return \Illuminate\Http\Response
     */
    public function show(AdditionalDescription $additionalDescription)
    {
        $data = $additionalDescription->load('product');
        return Resp::Success('تم', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdditionalDescription  $additionalDescription
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdditionalDescriptionRequest $request, AdditionalDescription $additionalDescription)
    {
        $validate = (object) $request->validated();

        foreach ($validate as $key => $value) {

            if ($validate->$key == 'expireDate') {

                $additionalDescription->expireDate = date('Y-m-d', strtotime($validate->expireDate));
            }

            $additionalDescription->$key = $value;
        }

        try {
            $additionalDescription->save();

            return Resp::Success('تم التحديث بنجاح', $additionalDescription);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdditionalDescription  $additionalDescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdditionalDescription $additionalDescription)
    {
        $additionalDescription->delete();

        return Resp::Success('تم الحذف', $additionalDescription);
    }
}
