<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductsPhotosRequest extends FormRequest
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
            'photo' => 'required',
            'photo.*' => 'image|mimes:jpg,jpeg,png',
            'product_id' => 'required|integer|exists:products,id'
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
            'photo.required' => 'حقل الصور مطلوب',
            'photo.*.image' => 'الصورة يجب ان تكون من النوع صورة',
            'product_id.required' => 'حقل رقم المنتج مطلوب',
            'product_id.integer' => 'حقل رقم المنتج يجب ان يكون من النوع رقم',
            'product_id.exists' => 'رقم المنتج غير موجود',
        ];
    }
}
