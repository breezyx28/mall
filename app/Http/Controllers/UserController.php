<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\UpdateAccountRequest;

class UserController extends Controller
{
    public function updateAccount(UpdateAccountRequest $request, Account $account)
    {

        $validate = $request->validated();

        if ($validate['password']) {
            unset($validate['password']);
        }

        $acc = new \App\Models\Account();

        $user = auth()->user()->id;

        try {
            $acc->where(['user_id' => $user, 'id' => $account])->update($validate);

            return Resp::Success('تم إنشاء حساب مستخدم بنجاح', $acc);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e);
        }
    }
}
