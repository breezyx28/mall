<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id, $content;

    public function __construct($user_id, $type)
    {
        $this->user_id = $user_id;

        $message = [
            'block' => 'تم إيقاف حسابك الرجاء التواصل مع الدعم الفني',
            'deactivate' => 'لقد قمت بإيقاف حسابك',
            'welcome' => 'مرحبا بك في مولكم',
            'verify' => 'تم إرسال رسالة التأكيد , الرجاء تأكيد الحساب',
            'delete' => 'تم حذف حسابك من قائمة المستخدمين لدينا للإستفسار الرجاء التواصل مع الدعم الفني',
        ][$type];

        $this->content = ['title' => $type, 'content' => $message];
    }
}
