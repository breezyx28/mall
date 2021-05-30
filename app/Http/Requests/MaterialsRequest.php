<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MaterialsRequest extends FormRequest
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
            'materialName' => 'required|string|max:191',
            'category_id' => 'required|exists:categories,id'
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
            'materialName.required' => 'حقل اسم الخامة مطلوب',
            'materialName.string' => 'حقل اسم الخامة يجب ان يكون من النوع نص',
            'materialName.max' => 'حقل الخامة تعدى الطول المسموح',
            'category_id.required' => 'حقل رقم الصنف المرجع مطلوب',
            'category_id.exists' => 'حقل رقم الصنف المرجعي غير موجود  في السجلات',
        ];
    }
}
