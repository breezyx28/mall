<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Helper\ResponseMessage as Resp;
use App\Http\Requests\RolesRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = \App\Models\Role::all();

        return Resp::Success('تم بنجاح', $all);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolesRequest $request)
    {
        $validate = (object) $request->validated();

        $role = new \App\Models\Role();

        foreach ($validate as $key => $value) {
            $role->$key = $value;
        }

        try {
            $role->save();
            return Resp::Success('تم إنشاء الصلاحية بنجاح', $role);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $Role)
    {
        return Resp::Success('تم بنجاح', $Role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $Role)
    {
        $validate = (object) $request->validated();

        foreach ($validate as $key => $value) {
            $Role->$key = $value;
        }

        try {
            $Role->save();
            return Resp::Success('تم تحديث البيانات بنجاح', $Role);
        } catch (\Exception $e) {
            return Resp::Error('حدث خطأ ما', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $Role)
    {
        try {
            $Role->delete();
            return Resp::Success('تم الحذف', $Role);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th);
        }
    }
}
