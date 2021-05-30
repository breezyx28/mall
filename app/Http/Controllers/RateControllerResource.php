<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\RateRequest;
use App\Http\Requests\UpdateRateRequest;
use App\Models\Product;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RateControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rates = \App\Models\Rate::with('user', 'product')->all();
        return Resp::Success('تم بنجاح', $rates);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RateRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $rateBase = 100;
        $ratersNumber = \App\Models\Rate::where('user_id', $product->id)->count();
        $totalRates = \App\Models\Rate::where('user_id', $product->id)->sum();

        $percent = ((($totalRates) / ($rateBase * $ratersNumber)) * 100);

        return Resp::Success('تم', $product->with('rate')->get()->concat(['total_rate' => $percent]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRateRequest $request, Product $rate)
    {
        // get validated request
        $validate = (object) $request->validated();

        DB::beginTransaction();
        try {
            // save rate first
            $product = \App\Models\Rate::updateOrCreate(
                ['user_id' => auth()->user()->id, 'product_id' => $rate->id],
                ['rate' => $validate->rate]
            );

            // update product
            // $product = $rate->increment('rate', $validate->rate);


            DB::commit();

            return Resp::Success('تم', $product);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        //
    }
}
