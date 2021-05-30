<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\AdminUpdateUserRequest;
use App\Http\Requests\updateUsersRequest;
use App\Http\Requests\UsersRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = \App\Models\User::all();

        return Resp::Success('تم بنجاح', $all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)
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
            $user->thumbnail = $request->file('thumbnail')->storePublicly('Profile');
        }
        $user->role_id = 3;
        $user->password = Hash::make($request->password);

        try {
            $user->save();

            event(new NotificationEvent($user->id, 'welcome'));
            event(new NotificationEvent($user->id, 'verify'));

            return Resp::Success('تم إنشاء مستخدم بنجاح', $user);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function show(User $User)
    {
        return Resp::Success('تم بنجاح', $User);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUpdateUserRequest $request, User $User)
    {
        $validate = (object) $request->validated();

        foreach ($validate as $key => $value) {

            if ($validate->$key == 'thumbnail') {
                // $user->thumbnail = null;
                $User->thumbnail = Str::of($request->file('thumbnail')->storePublicly('Profile'));
            }

            if ($validate->$key == 'birthDate') {

                $User->birthDate = date('Y-m-d', strtotime($validate->birthDate));
            }

            $User->$key = $value;
        }

        try {
            $User->save();
            if ($User->activity == 0) {
                event(new NotificationEvent($User->user_id, 'block'));
            }
            return Resp::Success('تم تحديث البيانات بنجاح', $User);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $User)
    {
        try {
            $User->delete();

            event(new NotificationEvent($User->id, 'delete'));

            return Resp::Success('تم الحذف', $User);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th);
        }
    }
}
