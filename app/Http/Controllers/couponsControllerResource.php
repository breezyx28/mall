<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\CouponsRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class couponsControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $all = Coupon::chunk(1000);

        return Resp::Success('تم', $all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CouponsRequest $request)
    {
        $validate = (object) $request->validated();
        $coupon = new \App\Models\Coupon();

        foreach ($validate as $key => $value) {
            if ($validate->$key == 'photo') {
                $coupon->photo = Str::of($request->file('photo')->storePublicly('Coupons'));
            }
            $coupon->$key = $value;
        }
        try {
            $coupon->save();
            return Resp::Success('تم', $coupon);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        return Resp::Success('تم', $coupon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $validate = (object) $request->validated();

        foreach ($validate as $key => $value) {
            if ($validate->$key == 'photo') {
                $coupon->photo = Str::of($request->file('photo')->storePublicly('Coupons'));
            }
            $coupon->$key = $value;
        }
        try {
            $coupon->save();
            return Resp::Success('تم', $coupon);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return Resp::Success('تم', $coupon);
    }
}
