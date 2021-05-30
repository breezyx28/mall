<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

class VerificationController extends Controller
{
    private $code, $phone;
    protected const api_key = 'bWFsbGNvbS5pdDIwMjFAZ21haWwuY29tOmVMVmwjMiZURlk=';

    public function __construct($code = 0)
    {
        $this->code = $code;
    }

    private function cleanPhone($phone)
    {
        $error = false;

        if (!strlen($phone) == 10) {
            $error = true;
        }
        if ($phone[0] == '0') {
            $phone = substr_replace($phone, "249", 0, 1);
        }
        if (!is_numeric($phone)) {
            $error = true;
        }
        if (!$error) {
            return $phone;
        } else {
            return $error;
        }
    }

    public function sendCode($phone)
    {
        $this->phone = $this->cleanPhone($phone);

        $http = Http::get('https://mazinhost.com/smsv1/sms/api', [
            'action' => 'send-sms',
            'api_key' => self::api_key,
            'to' => $this->phone,
            'from' => 'mallcom',
            'sms' => "$this->code رمز التأكيد",
            'unicode' => '1'
        ]);

        $resp =  $http->json();

        if (isset($resp['balance']) && $resp['balance'] < 1) {

            return Resp::Error('SMS Error', 'الرجاء التواصل مع إدارة النظام');
        }

        if ($resp['code'] != "ok") {

            return Resp::Error('SMS Error', 'خطأ في عملية ارسال رمز التأكيد');
        }

        return Resp::Success('SMS Sent Successfuly', 'تم إرسال رمز التأكيد إلى ' . $phone,);
    }

    public function verifyAccount(Request $request)
    {
        $validate = (object) $request->validate([
            'id' => 'required|integer',
            'verificationCode' => 'required|integer'
        ]);

        try {
            // validate request into DB
            $d = \App\Models\Verification::where(['user_id' => $validate->id, 'code' => $validate->verificationCode])->firstOr(function () {
                throw new \Exception("البيانات غير صحيحة");
            });

            //code...
            $verf = \App\Models\Verification::where(['user_id' => $validate->id, 'code' => $validate->verificationCode])->update(['verified' => 1]);

            $user = \App\Models\User::find($validate->id);
            // $token = JWTAuth::fromUser($user);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'تم بنجاح',
                'data' => $user,
                'token' => $token,
            ], 200);

            return Resp::Success('تم تاكيد الحساب بنجاح',);
        } catch (\Throwable $th) {
            //throw $th;
            return Resp::Error('البيانات غير صحيحة', $th->getMessage());
        }
    }
}
