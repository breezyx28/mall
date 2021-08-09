<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SizesRequest extends FormRequest
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
            'unit' => 'required_without:measure|string|max:191',
            'size' => 'required_without:measure|string|max:191',
            'measure' => 'required_without:unit,size|string|max:191',
            'measureType' => 'required|string|max:4',
            'category_name' => 'required|exists:categories,name|string'
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
            'unit.required' => 'حقل وحدة القياس مطلوب',
            'unit.string' => 'حقل وحدة القياس يجب ان يكون نص',
            'unit.max' => 'حقل وحدة القياس تجاوز الطول المسموح به',
            'size.required' => 'حقل المقاس مطلوب',
            'size.string' => 'حقل المقاس يجب ان يكون نص',
            'size.max' => 'حقل المقاس تجاوز الطول المسموح به',
            'measure.required' => 'حقل المقاس مطلوب',
            'measure.string' => 'حقل المقاس يجب ان يكون نص',
            'measure.max' => 'حقل المقاس تجاوز الطول المسموح به',
            'measureType.required' => 'حقل نوع المقاس مطلوب',
            'measureType.string' => 'حقل نوع المقاس يجب ان يكون نص',
            'measureType.max' => 'حقل المقاس تجاوز الطول المسموح به وهو ال4',
            'category_id.required' => 'حقل رقم الصنف المرجعي مطلوب',
            'category_id.exists' => 'حقل رقم الصنف المرجعي غير موجود في السجلات',
            'category_id.integer' => 'حقل رقم الصنف المرجعي يجب ان يكون رقم صحيح',
        ];
    }
}
