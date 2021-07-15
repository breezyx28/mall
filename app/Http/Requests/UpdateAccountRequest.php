<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAccountRequest extends FormRequest
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
            'cardName' => 'string|max:100',
            'cardNumber' => 'numeric|digits_between:16,20',
            'expireDate' => 'date',
            'password' => 'required|string'
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
            'cardName.string' => 'إسم البطاقة يجب ان يكون نص',
            'cardName.max' => 'إسم البطاقة تجواز الحد الأقصى',
            'cardNumber.integer' => 'رقم البطاقة يجب ان يكون رقمي',
            'cardNumber.digits_between' => ' رقم البطاقة أقل من 16 رقم أو أكبر من 20 رقم',
            'expireDate.date' => 'تاريخ الصلاحية غير صحيح',
            'password.required' => 'كلمة السر مطلوبة',
            'password.string' => 'كلمة السر يجب ان تكون نص',
        ];
    }
}
