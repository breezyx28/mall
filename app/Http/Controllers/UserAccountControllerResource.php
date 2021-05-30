<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use Illuminate\Support\Facades\Hash;

class UserAccountControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $acc = \App\Models\Account::with('user')->where('user_id', auth()->user()->id)->get();
        return Resp::Success('تم بنجاح', $acc);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountRequest $request)
    {
        $validate = (object) $request->validated();

        $acc = new \App\Models\Account();

        foreach ($validate as $key => $value) {
            $acc->$key = $value;
        }

        $acc->user_id = auth()->user()->id;

        try {
            $acc->save();
            return Resp::Success('تم إنشاء حساب مستخدم بنجاح', $acc);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        if ($account->user_id == auth()->user()->id) {
            return Resp::Success('تم', $account);
        }
        return Resp::Error('انت لا تملك هذا الحساب');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $validate = (object) $request->validated();

        $acc = $account;

        if (!Hash::check($validate->password, auth()->user()->password)) {
            return Resp::Error('كلمة السر غير صحيحة');
        }

        foreach ($validate as $key => $value) {
            if (isset($validate->password)) {
                unset($validate->password);
            }
            $acc->$key = $value;
        }

        try {
            $acc->save();
            return Resp::Success('تم تحديث حساب مستخدم بنجاح', $acc);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        if ($account->user->contain(auth()->user()->id)) {
            $account->delete();
            return Resp::Success('تم الحذف', $account);
        }
        return Resp::Error('انت لا تملك هذا الحساب');
    }
}
