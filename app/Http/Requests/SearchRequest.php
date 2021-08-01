<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchRequest extends FormRequest
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
            'filter' => 'array',
            'filter.color' => 'string|max:100',
            'filter.countryOfMade' => 'string|max:100',
            'filter.company' => 'string|max:100',
            'filter.discount' => 'integer',
            'filter.weight' => 'integer',
            // 'filter.expireDate' => 'date',
            'filter.price' => 'array',
            'filter.price.from' => 'integer',
            'filter.price.to' => 'integer',
            'filter.rate' => 'integer',
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
            'filter.array' => 'حقل التنقية يجب ان يكون مصفوفة',
            'filter.color.string' => 'حقل اللون يجب ان يكون نص',
            'filter.countryOfMade.string' => 'حقل بلد الصنع يجب ان يكون نص',
            'filter.countryOfMade.max' => 'حقل بلد الصنع تجاوز الطول المسموح',
            'filter.weight.integer' => 'حقل الوزن يجب ان يكون رقم صحيح',
            'filter.discount.integer' => 'حقل التخفيض يجب ان يكون رقم صحيح',
            'filter.expireDate.date' => 'حقل تاريخ الصلاحية يجب ان يكون تاريخ',
            'filter.price.array' => 'حقل السعر يجب ان يكون مصفوفة',
            'filter.price.from.integer' => 'حقل سعر البداية يجب ان يكون رقم صحيح',
            'filter.price.to.integer' => 'حقل سعر النهاية يجب ان يكون رقم صحيح',
            'filter.rate.integer' => 'حقل التفييم يجب ان يكون رقم صحيح',
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
