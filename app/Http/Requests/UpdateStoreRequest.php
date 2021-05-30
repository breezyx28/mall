<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|max:191',
            'thumbnail' => 'image|mimes:jpg,jpeg,png',
            'phone' => 'unique:stores,phone|digits:10',
            'address' => 'string|max:191',
            'bio' => 'string|max:191',
            'activity' => 'boolean',
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
            'name.string' => 'حقل اسم المتجر يجب ان يكون من النوع نص',
            'thumbnail.image' => 'حقل صورة المتجر يجب ان تكون من النوع صورة',
            'thumbnail.mimes' => 'حقل صورة المتجر يجب انت تكون ضمن jpg,jpeg,png',
            'phone.unique' => 'هاتف المتجر مستخدم مسبقا',
            'phone.digits' => 'حقل هاتف المتجر يجب ان يكون من النوع ارقام',
            'address.string' => 'حقل عنوان المتجر يجب ان يكون من النوع نص',
            'user_id.exists' => 'حقل رقم مستخدم المتجر غير متوفر في السجلات',
            'user_id.integer' => 'حقل رقم مستخدم المتجر يجب ان يكون من النوع رقم صحيح',
            'bio.string' => 'حقل معلومات المتجر يجب ان يكون من النوع نص',
            'activity.boolean' => 'حقل إلغاء نشاط المتجر يجب ان يكون من النوع قيمة منطقية',
        ];
    }
}
