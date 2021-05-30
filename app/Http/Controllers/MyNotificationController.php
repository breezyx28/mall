<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Helper\ResponseMessage as Resp;
use Illuminate\Http\Request;

class MyNotificationController extends Controller
{
    public function myNotification()
    {

        $all = Notification::with(['user' => function ($query) {
            return $query->where('id', 5);
        }])->get();

        return Resp::Success('تم', $all);
    }
}
