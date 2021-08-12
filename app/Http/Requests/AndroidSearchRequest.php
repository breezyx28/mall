<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AndroidSearchRequest extends FormRequest
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
            'search' => 'required|string|max:100',
            'sort' => ['string', 'max:191', Rule::in(['lowerPrice', 'higherPrice', 'newFirst'])],
            'color' => 'string|max:100',
            'countryOfMade' => 'string|max:100',
            'company' => 'string|max:100',
            'discount' => 'integer',
            'weight' => 'integer',
            // 'expireDate' => 'date',
            'price_from' => 'integer',
            'price_to' => 'integer',
            'rate' => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'search.required' => 'حقل البحث مطلوب',
            'search.string' => 'حقل البحث يجب ان يكون نص',
            'search.max' => 'حقل البحث تجاوز الطول المسموح للنص',
            'sort.string' => 'حقل الترتيب مطلوب',
            'sort.max' => 'حقل الترتيب مطلوب',
            'sort.in' => 'حقل الترتيب مطلوب',
            'color.string' => 'حقل اللون يجب ان يكون نص',
            'countryOfMade.string' => 'حقل بلد الصنع يجب ان يكون نص',
            'countryOfMade.max' => 'حقل بلد الصنع تجاوز الطول المسموح',
            'weight.integer' => 'حقل الوزن يجب ان يكون رقم صحيح',
            'discount.integer' => 'حقل التخفيض يجب ان يكون رقم صحيح',
            'expireDate.date' => 'حقل تاريخ الصلاحية يجب ان يكون تاريخ',
            'price_from.integer' => 'حقل سعر البداية يجب ان يكون رقم صحيح',
            'price_to.integer' => 'حقل سعر النهاية يجب ان يكون رقم صحيح',
            'rate.integer' => 'حقل التفييم يجب ان يكون رقم صحيح',
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
}
