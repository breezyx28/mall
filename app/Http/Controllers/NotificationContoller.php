<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationContoller extends Controller
{
    public function readOne(Notification $id)
    {

        try {
            //code...
            $id->update(['isReaded' => true]);
            return Resp::Success('تم تحديث كمقروء', $id);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    public function readAll()
    {
        $user = auth()->user()->id;

        try {
            \App\Models\Notification::where('user_id', $user)->update(['isReaded' => true]);
            return Resp::Success('تم قراءة جميع الإشعارات');
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }
}
