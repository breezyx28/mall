<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductSizeRequest extends FormRequest
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


    public function rules()
    {
        return [
            'sizes_array' => 'array',
            'sizes_array.*' => 'required_with:sizes_array|exists:sizes,id|string',
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
            'sizes_array.required' => 'حقل مصفوفة المقاسات مطلوب',
            'sizes_array.array' => 'حقل مصفوفة المقاسات يجب ان يكون من النوع مصفوفة',
            'sizes_array.*.required_with' => 'حقل رقم المقاس المرجعي مطلوب مع حقل مصفوفةالمقاسات',
            'sizes_array.*.exists' => 'حقل رقم المقاس المرجعي غير متوفر',
        ];
    }
}
