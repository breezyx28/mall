<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use Illuminate\Support\Facades\Hash;

class AccountsControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $acc = \App\Models\Account::all();
        return Resp::Success('تم بنجاح', $acc);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountRequest $request)
    {
        $auth = auth()->user();

        $account = new \App\Models\Account();

        $validate = (object) $request->validated();

        foreach ($validate as $key => $value) {
            $account->$key = $value;
        }

        try {
            $account->user_id = $auth->id;
            $account->save();
            return Resp::Success('تم', $account);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e->getMessage());
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
        return Resp::Success('تم بنجاح', $account);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        //
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

        if ($acc->user_id != auth()->user()->id) {
            return Resp::Error('لا تملك هذا الحساب');
        }

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
        $auth = auth()->user();

        if ($account->user_id == $auth->id) {
            $account->delete();
            return Resp::Success('تك الحذف بنجاح', $account);
        }
        return Resp::Error('انت لا تملك هذا الحساب', null);
    }
}
