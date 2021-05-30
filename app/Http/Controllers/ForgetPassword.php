<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use Illuminate\Support\Str;
use App\Helper\SendSMS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ForgetPassword extends Controller
{
    public function forgetPassword(Request $request)
    {
        $validate = (object) $request->validate([
            'phoneNumber' => 'required|exists:users,phone|digits:10',
        ], [
            'phoneNumber.required' => 'حقل الهاتف مطلوب',
            'phoneNumber.exists' => 'رقم الهاتف غير موجود ',
            'phoneNumber.digits' => 'يجب ان يكون طول رقم الهاتف 10 خانات',
        ]);

        $reset = \App\Models\RestPassword::find(substr($validate->phoneNumber, 1)) ?? new \App\Models\RestPassword();

        $newPass = Str::random(10);

        DB::beginTransaction();
        try {
            // send message first
            SendSMS::Send(['phone' => $validate->phoneNumber, 'sms' => "$newPass رمز التأكيد لكلمة السر"]);

            // save data to reset_password table
            $reset->code = $newPass;
            $reset->phone = substr($validate->phoneNumber, 1);
            $reset->set = 0;

            $reset->save();

            DB::commit();
            return Resp::Success('تم إرسال الرسالة بنجاح', null);
        } catch (\Throwable $th) {
            DB::rollback();
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    public function newPassword(Request $request)
    {

        $validate = $request->validate([
            'phoneNumber' => 'required|exists:reset_password,phone|digits:10',
            'code' => 'required|exists:reset_password,code',
            'newPassword' => 'required|string|confirmed|max:191',
            'newPassword_confirmation' => 'required|string|confirmed|max:191',
        ], [
            'phoneNumber.required' => 'حقل الهاتف مطلوب',
            'phoneNumber.exists' => 'رقم الهاتف غير موجود ',
            'phoneNumber.digits' => 'يجب ان يكون طول رقم الهاتف 10 خانات',
            'code.required' => 'حقل رمز التأكيد مطلوب',
            'code.exists' => 'رمز التأكيد غير صحيح ',
            'newPassword.max' => 'حقل كلمة السر تجاوز الحد المسموح لعدد الحروف',
            'newPassword.string' => 'حقل كلمة السر يجب ان يكون نص',
            'newPassword.confirmed' => 'حقل تأكيد كلمة السر غير متطابق',
        ]);

        if (\App\Models\RestPassword::where(['phone' => substr($validate['phoneNumber'], 1), 'code' => $validate['code'], 'set' => 0])->exists()) {

            \App\Models\User::where('phone', $validate['phoneNumber'])->update(['password' => Hash::make($validate['newPassword'])]);
            \App\Models\RestPassword::where('phone', $validate['phoneNumber'])->update(['set' => 1]);
            return Resp::Success('تم تعيين كلمة السر بنجاح');
        }

        return Resp::Error('البيانات المدخلة غير صحيحة أو انك قمت مسبقا بإستخدام نفس البيانات');
    }
}
