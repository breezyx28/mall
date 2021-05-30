<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;
use App\Helper\ResponseMessage as Resp;
use Exception;
use Illuminate\Support\Str;

class SendSMS
{
    protected const api_key = 'bWFsbGNvbS5pdDIwMjFAZ21haWwuY29tOmVMVmwjMiZURlk=';

    public static function Send($arr = [
        'sms' => '',
        'phone' => ''
    ])
    {
        $http = Http::get('https://mazinhost.com/smsv1/sms/api', [
            'action' => 'send-sms',
            'api_key' => self::api_key,
            'to' =>  Str::replaceFirst($arr['phone'][0], '249', "$arr[phone]"),
            'from' => 'mallcom',
            'sms' => $arr['sms'],
            'unicode' => '1'
        ]);

        $resp =  $http->json();

        if (isset($resp['balance']) && $resp['balance'] < 1) {

            throw new Exception('الرجاء التواصل مع إدارة النظام');
        }

        if ($resp['code'] != "ok") {

            throw new Exception('خطأ في عملية ارسال الرسالة');
        }

        return true;
    }
}
