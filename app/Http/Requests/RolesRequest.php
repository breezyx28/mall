<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RolesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->role_id == 1) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'roleCode' => 'required|unique:roles|integer|digits:1|between:0,3',
            'position' => ['required', Rule::in(['admin', 'store', 'user']), 'unique:roles', 'string', 'max:100']
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
            'roleCode.required' => 'حقل رقم الصلاحية مطلوب',
            'roleCode.unique' => 'حقل رقم الصلاحية مستخدم',
            'roleCode.integer' => 'حقل رقم الصلاحية يجب ان يكون رقم صحيح',
            'roleCode.digits' => 'حقل رقم الصلاحية يجب ان لا يتجاوز خانة رقمية واحدة',
            'roleCode.between' => 'حقل رقم الصلاحية يجب ان يكون بين ال0 و ال3',
            'position.required' => 'حقل الوظيفة مطلوب',
            'position.unique' => 'حقل الوظيفة مستخدم مسبقا',
            'position.string' => 'حقل الوظيفة يجب ان يكون نص',
            'position.max' => 'حقل الوظيفة تجاوز الطول النصي المصرح به',
            'position.in' => 'حقل الوظيفة يجب ان يكون ضمن ال user, admin, store',
        ];
    }
}
