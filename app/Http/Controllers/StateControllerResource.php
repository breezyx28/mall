<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\StatesRequest;
use App\Http\Requests\UpdateStateRequest;
use App\Models\State;

class StateControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = \App\Models\State::all();

        return Resp::Success('تم', $states);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StatesRequest $request)
    {
        $validate = $request->validated();

        $state = new State();

        foreach ($validate as $key => $value) {
            $state->$key = $value;
        }

        try {
            $state->save();
            return Resp::Success('تم إنشاء الولاية بنجاج', $state);
        } catch (\Throwable $th) {
            return Resp::Success('حدث خطأ', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\State  $State
     * @return \Illuminate\Http\Response
     */
    public function show(State $State)
    {
        return Resp::Success('تم', $State);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $State
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStateRequest $request, State $State)
    {
        $validate = $request->validated();

        foreach ($validate as $key => $value) {
            $State->$key = $value;
        }

        try {
            $State->save();
            return Resp::Success('تم تحديث الولاية بنجاج', $State);
        } catch (\Throwable $th) {
            return Resp::Success('حدث خطأ', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State  $State
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $State)
    {
        $State->delete();
        return Resp::Success('تم الحذف', $State);
    }
}
