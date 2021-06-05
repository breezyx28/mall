<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use Illuminate\Http\Request;
use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\updateUsersRequest;
use App\Rules\phoneRule;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\VerificationController as SMS;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Events\sendVerificationEvent;
use App\Notifications\VerifiyAccount;

class LoginController extends Controller
{

    public function Login(Request $request)
    {

        $credintials = [
            'phone' => $request->phone,
            'password' => $request->password,
        ];

        $token = null;

        if (!$token = JWTAuth::attempt($credintials)) {
            return Resp::Error('خطأ في كلمة السر او رقم الهاتف');
        }

        $auth = auth()->user();
        $user = \App\Models\User::where('id', $auth->id)->with('favourit')->get();

        return response()->json([
            'success' => true,
            'message' => 'تم بنجاح',
            'data' => $user,
            'token' => $token,
        ], 200);
    }

    public function profile()
    {
        $user = auth()->user()->id;

        return Resp::Success('تم', \App\Models\User::with('favourit')->where('id', $user)->get());
    }

    public function storeProfile()
    {
        $user = auth()->user()->id;

        return Resp::Success('تم', \App\Models\Store::where('user_id', $user)->get());
    }

    public function updateProfile(updateUsersRequest $request)
    {
        $validate = (object) $request->validated();

        $auth = auth()->user();

        $user = \App\Models\User::find($auth->id);

        foreach ($validate as $key => $value) {

            if ($validate->$key == 'birthDate') {

                $user->birthDate = date('Y-m-d', strtotime($validate->birthDate));
            }

            $user->$key = $value;
        }

        if (isset($request['thumbnail'])) {
            // $user->thumbnail = null;
            $user->thumbnail = Str::of($request->file('thumbnail')->storePublicly('Profile'));
        }

        try {
            $user->save();

            if ($user->activity == 0) {
                event(new NotificationEvent($user->id, 'deactivate'));
            }

            return Resp::Success('تم تحديث البيانات بنجاح', $user);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = auth()->user();

        $validated = (object) $request->validated();

        if (!Hash::check($validated->oldPassword, $user->password)) {
            return Resp::Error('كلمة السر القديمة غير صحيحة');
        }

        $newPassword = Hash::make($validated->newPassword);

        try {

            \App\Models\User::find($user->id)->update(['password' => $newPassword]);

            return Resp::Success('تم تغيير كلمة السر بنجاح', "new pasword is : $validated->newPassword");
        } catch (\Exception $e) {
            return Resp::Error('لم يتم تعين كلمة السر', $e->getMessage());
        }
    }

    // logout process
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return Resp::Success('تم تسجسل الخروج بنجاح');
        } catch (JWTException $exception) {
            return Resp::Error('لا يمكن إجراء عملية تسجيل الخروج الآن ...', $exception);
        }
    }

    protected function createNewToken($token)
    {

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 3600,
            'user' => auth()->user()
        ]);
    }

    public function accountCheck(Request $request)
    {
        $validated = (object) $request->validate(([
            'phoneNumber' => ['required', 'numeric', new phoneRule()]
        ]));

        // check for the phoneNummber in DB
        $check  = \App\Models\User::where('phone', $validated->phoneNumber)->firstOr(function () {
            return false;
        });

        // if user exists
        if ($check) {
            // check user verification on verification Model
            $verification = \App\Models\Verification::where(['user_id' => $check->id])->firstOr(function () {
                return false;
            });

            $user = \App\Models\User::find($check->id);

            // if user exists but not exists on verification model
            if (!$verification) {
                DB::beginTransaction();
                try {
                    //code...
                    event(new NotificationEvent($user->id, 'verify'));
                    event(new sendVerificationEvent($user));
                    DB::commit();
                    return Resp::Success('تم ارسال رسالة التأكيد بنجاح', $user->id);
                } catch (\Throwable $th) {
                    //throw $th;
                    DB::rollback();
                    return Resp::Error('حدث خطا ما اثناء ارسال رسالة التاكيد ... الرجاء المحاولة مرة أخرى');
                }
            }

            $code = rand(100000, 999999);
            $sms = new SMS($code);

            // if user exists and verified
            if ($verification->verified) {

                // send verification code and update code in Verifi... Model
                try {
                    // send sms first
                    $sms->sendCode($check['phone']);
                    $verf = \App\Models\Verification::find($verification->id);
                    $verf->code = $code;
                    $verf->save();

                    return Resp::Success('تم إرسال الرقم بنجاح', $user->id);
                } catch (\Exception $e) {
                    return Resp::Error('خطأ في حفظ الرمز التاكيد ... الرجاء المحاولة لاحقا', $e->getMessage());
                }
            } else {
                // send verification code and update code in Verifi... Model
                try {
                    $verf = \App\Models\Verification::find($verification->id);
                    $verf->code = $code;
                    $verf->save();

                    // send sms first
                    $sms->sendCode($check['phone']);
                    $user->notify(new VerifiyAccount($user->id, $code));

                    return Resp::Success('تم التأكد بنجاح ... قم بتأكيد حسابك أولا', $user->id);
                } catch (\Exception $e) {
                    return Resp::Error('خطأ في حفظ الرمز التاكيد ... الرجاء المحاولة مرة أخرى', $e->getMessage());
                }
            }
        } else {

            return Resp::Error('المستخدم غير موجود');
        }
    }
}
