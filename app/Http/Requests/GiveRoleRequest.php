<?php

namespace App\Http\Requests;

use App\Helper\ResponseMessage as Resp;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GiveRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->role_id === 1) {
            return true;
        }
        return Resp::Error('غير مصرح');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'permission' => 'required|in:admin,user|string|max:100',
            'user_id' => 'required|exists:users,id|integer'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $messages = [];
        foreach ($errors->all() as $message) {
            $messages[] = $message;
        }
        throw new HttpResponseException(response()->json(['success' => false, 'errors' => $messages], 200));
    }

    public function messages()
    {
        return [
            'permission.required' => 'حقل الصلاحية مطلوب',
            'permission.unique' => 'حقل الصلاحية مستخدم مسبقا',
            'permission.string' => 'حقل الصلاحية يجب ان يكون نص',
            'permission.max' => 'حقل الصلاحية تجاوز الطول النصي المصرح به',
            'permission.in' => 'حقل الصلاحية يجب ان يكون ضمن ال user, admin',
            'user_id.required' => 'حقل رقم مستخدم المتجر مطلوب',
            'user_id.exists' => 'حقل رقم المستخدم غير متوفر في السجلات',
            'user_id.integer' => 'حقل رقم المستخدم يجب ان يكون من النوع رقم صحيح',
        ];
    }
}
