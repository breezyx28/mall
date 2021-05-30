<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\AdsRequest;
use App\Http\Requests\UpdateAdRequest;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdsControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = Ad::with('product', 'category')->get();
        return Resp::Success('تم', $all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdsRequest $request)
    {
        $validate = (object) $request->validated();
        $ad = new Ad();

        foreach ($validate as $key => $value) {
            $ad->$key = $value;
        }

        $ad->photo = Str::of($request->file('photo')->storePublicly('Ads'));

        try {
            $ad->save();
            return Resp::Success('تم', $ad);
        } catch (\Throwable $th) {
            //throw $th;
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ad  $ad
     * @return \Illuminate\Http\Response
     */
    public function show(Ad $ad)
    {
        $ad->load('product', 'category');
        return Resp::Success('تم', $ad);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ad  $ad
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdRequest $request, Ad $ad)
    {
        $validate = $request->validated();

        foreach ($validate as $key => $value) {

            if ($ad->$key == 'photo') {
                $ad->photo = Str::of($request->file('photo')->storePublicly('Ads'));
            }

            $ad->$key = $value;
        }

        try {
            $ad->save();
            return Resp::Success('تم التحديث بنجاح', $ad);
        } catch (\Throwable $th) {
            //throw $th;
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ad  $ad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ad $ad)
    {
        $ad->delete();
        return Resp::Success('تم الحذف', $ad);
    }
}
