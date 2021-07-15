<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\dateFormatRule;

class AccountRequest extends FormRequest
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
            'cardName' => 'required|string|max:100',
            'cardNumber' => 'required|unique:accounts,cardNumber|numeric|digits_between:16,20',
            'expireDate' => ['required', 'date', new dateFormatRule()]
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
            'cardName.required' => 'إسم البطاقة مطلوب',
            'cardName.string' => 'إسم البطاقة يجب ان يكون نص',
            'cardName.max' => 'إسم البطاقة تجواز الحد الأقصى',
            'cardNumber.required' => 'رقم البطاقة مطلوب',
            'cardNumber.integer' => 'رقم البطاقة يجب ان يكون رقمي',
            'cardNumber.digits_between' => ' رقم البطاقة أقل من 16 رقم أو أكبر من 20 رقم',
            'expireDate.required' => 'تاريخ الصلاحية البطاقة مطلوب',
            'expireDate.date' => 'تاريخ الصلاحية غير صحيح',
        ];
    }
}
