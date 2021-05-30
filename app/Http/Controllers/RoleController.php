<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Http\Controllers\Controller;
use App\Http\Requests\GiveRoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function givePermission(GiveRoleRequest $request)
    {
        $validate = (object) $request->validated();

        $user = \App\Models\User::find($validate->user_id);

        if ($user->role_id == 1) {
            $count = \App\Models\User::where('role_id', 1)->count();

            if ($count == 1) {
                return Resp::Error('لا يمكن إجراء العملية على أخر مدير');
            }
        }
        $role = \App\Models\Role::where('position', $validate->permission)->get();
        // return Resp::Success('ok', $role);

        try {

            $user->role_id = $role[0]->id;
            $user->save();

            return Resp::Success('تم تحديث الصلاحية', $user);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }
}
