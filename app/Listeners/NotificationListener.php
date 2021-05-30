<?php

namespace App\Listeners;

// use App\Helper\ResponseMessage as Resp;

use App\Helper\ResponseMessage;
use App\Models\Notification;

class NotificationListener
{
    public function handle($event)
    {

        # listener is about saving the user notification
        $noti = new Notification();

        $noti->title = $event->content['title'];
        $noti->content = $event->content['content'];
        $noti->user_id = $event->user_id;
        try {
            $noti->save();
        } catch (\Throwable $th) {
            ResponseMessage::Error('Notification Event Error', $th->getMessage());
        }
    }
}
