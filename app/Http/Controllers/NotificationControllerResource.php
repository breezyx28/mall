<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\NotificationsRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = Notification::whereHas('user', function ($q) {
            $q->where('user_id', auth()->user()->id);
        })->get();

        return Resp::Success('تم', $all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotificationsRequest $request)
    {
        $validate = (object) $request->validated();
        $notification = new Notification();

        return Resp::Success('ok', $validate);

        foreach ($validate as $key => $value) {
            $notification->$key = $value;
        }

        try {
            $notification->save();
            return Resp::Success('تم', $notification);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $Notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $Notification)
    {
        return Resp::Success('تم', $Notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $Notification
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNotificationRequest $request, Notification $Notification)
    {
        $validate = (object) $request->validated();

        foreach ($validate as $key => $value) {
            $Notification->$key = $value;
        }

        try {
            $Notification->save();
            return Resp::Success('تم التحديث بنجاح', $Notification);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $Notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $Notification)
    {
        return Resp::Success('تم الحذف', $Notification);
    }
}
