<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Events\sendVerificationEvent;
use Illuminate\Http\Request;
use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\UsersRequest;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(UsersRequest $request)
    {
        $validate = (object) $request->validated();

        $user = new \App\Models\User();

        foreach ($validate as $key => $value) {

            if ($validate->$key == 'password') {
                $user->password = null;
            }

            if ($validate->$key == 'birthDate') {

                $user->birthDate = date('Y-m-d', strtotime($validate->birthDate));
            }

            $user->$key = $value;
        }

        if (isset($validate->thumbnail)) {
            // $user->thumbnail = null;
            $user->thumbnail = $request->file('thumbnail')->storePublicly('Profile');
        }
        $user->password = Hash::make($request->password);
        $user->role_id = 3;

        DB::beginTransaction();
        try {
            $user->save();

            event(new NotificationEvent($user->id, 'welcome'));
            event(new NotificationEvent($user->id, 'verify'));
            event(new sendVerificationEvent($user));
            DB::commit();
            return Resp::Success('تم إنشاء مستخدم بنجاح', $user);
        } catch (\Exception $e) {
            DB::rollback();
            return Resp::Error('حدث خطأ ما', $e);
        }
    }
}
