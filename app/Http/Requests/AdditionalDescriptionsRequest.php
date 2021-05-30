<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdditionalDescriptionsRequest extends FormRequest
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
            'color' => 'array',
            'color.*' => 'string|max:191',
            'weight' => 'integer',
            'for' => 'string|max:191',
            'company' => 'string|max:191',
            'expireDate' => 'date',
            'product_id' => 'required|integer|unique:additional_descriptions|exists:products,id',
            'countryOfMade' => 'string|max:191'
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
            'color' => 'حقل اللون يجب ان يكون من النوع مصفوفة',
            'color.*.string' => 'الألوان يجب ان تكون من النوع نص',
            'color.*.max' => 'أحد الألوان تجاوز الحد المسموح للطول',
            'weight.integer' => 'حقل الوزن يجب ان يكون من النوع رقم صحيح',
            'for.string' => 'حقل التخصيص يجب ان يكون من النوع نص',
            'for.max' => 'حقل التخصيص تجاوز الحد المسموح للطول',
            'company.string' => 'حقل الشركة يجب ان يكون من النوع نص',
            'company.max' => 'حقل الشركة تجاوز الحد المسموح للطول',
            'expireDate.date' => 'حقل تاريخ الإنتهاء يجب ان يكون من النوع تاريخ',
            'countryOfMade.string' => 'حقل بلد الصنع يجب ان يكون من النوع نص',
            'countryOfMade.max' => 'حقل بلد الصنع تجاوز الحد المسموح للطول',
            'product_id.required' => 'حقل رقم المنتج المرجعي مطلوب',
            'product_id.integer' => 'حقل رقم المنتج المرجعي  يجب ان يكون من النوع رقم صحيح',
            'product_id.exists' => 'حقل رقم المنتج المرجعي غير موجود في السجلات',
            'product_id.unique' => 'حقل رقم المنتج المرجعي مستخدم',
        ];
    }
}
