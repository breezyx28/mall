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
            'sizes_array' => 'required|array',
            'sizes_array.*.size' => 'required_with:sizes_array.*.unit|exists:sizes,size|string',
            'sizes_array.*.unit' => 'required_with:sizes_array.*.size|exists:sizes,unit|string',
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
            'sizes_array.*.size.string' => 'حقل المقاس يجب ان يكون نص',
            'sizes_array.*.unit.string' => 'حقل الوحدة يجب ان يكون نص',
            'sizes_array.*.size.required_with' => 'حقل المقاس مطلوب مع حقل الوحدة',
            'sizes_array.*.unit.required_with' => 'حقل الوحدة مطاوب مع حقل المقاس',
            'sizes_array.*.size.exists' => 'حقل الوحدة يجب ان يكون ضمن قائمة المقاسات',
            'sizes_array.*.unit.exists' => 'حقل الوحدة يجب ان يكون ضمن قائمة المقاسات',
        ];
    }
}
