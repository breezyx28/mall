<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductSizesRequest extends FormRequest
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
            'product_id' => 'exists:products,id',
            'sizes_array' => 'array',
            'sizes_array.*' => 'required_with:sizes_array|exists:sizes,id',
            // 'sizes_array.*.size' => 'required|string',
            // 'sizes_array.*.unit' => 'required|string',
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
            'product_id.required' => 'حقل رقم المنتج المرجعي مطلوب',
            'product_id.exists' => 'حقل رقم المنتج المرجعي غير موجود في السجلات',
            'sizes_array.required' => 'حقل مصفوفة المقاسات مطلوب',
            'sizes_array.array' => 'حقل مصفوفة المقاسات يجب ان يكون من النوع مصفوفة',
            'sizes_array.*.required' => 'حقل رقم المقاس المرجعي مطلوب',
            'sizes_array.*.exists' => 'حقل رقم المقاس المرجعي غير متوفر',
            // 'sizes_array.*.size.required' => 'حقل المقاس مطلوب',
            // 'sizes_array.*.size.string' => 'حقل المقاس يجب ان يكون نص',
            // 'sizes_array.*.unit.required' => 'حقل الوحدة مطلوب',
            // 'sizes_array.*.unit.string' => 'حقل الوحدة يجب ان يكون نص',
        ];
    }
}
