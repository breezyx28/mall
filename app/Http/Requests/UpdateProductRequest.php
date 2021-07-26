<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
            'name' => 'string|max:200',
            'price' => 'integer',
            'photo' => 'image|mimes:jpg,jpeg,png',
            'description' => 'string',
            'note' => 'string',
            'discount' => 'integer|min:0|max:100',
            'bar_code' => 'string|max:191',
            // 'addetionalPrice' => 'string',
            'offerText' => 'string|max:191',
            'inventory' => 'integer',
            'status' => 'boolean',
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
            'name.string' => 'إسم المنتج يجب ان يكون نص',
            'name.max' => 'إسم المنتج تجواز الحد الأقصى',
            'price.integer' => 'السعر يجب ان يكون رقمي',
            'photo.image' => 'الصورة يجب ان تكون من نوع صورة',
            'photo.mimes' => 'الصورة يحب ان تكون ضمن ال jpg png jpeg',
            'description.string' => 'وصف المنتج يجب ان يكون نص',
            'note.string' => 'الملاحظة يجب ان تكون نص',
            'discount.integer' => 'التخفيض يجب ان يكون رقمي',
            'discount.max' => 'التخفيض تجاوز الحد المسموح 100',
            'discount.min' => 'التخفيض اقل من الحد المسموح 0',
            'bar_code.string' => 'الباركود يجب ان تكون نص',
            'bar_code.max' => 'الباركود تجاوز الحد المسموح 100',
            'offerText.string' => 'العرض يجب ان يكون نص',
            'offerText.max' => 'العرض تجاوز الحد المسموح للكتابة',
            'inventory.integer' => 'المخزون يجب ان يكون رقمي',
            'status.boolean' => 'الحالة المرئية للمنتج يجب ان كون رقمية 0 أو 1',
        ];
    }
}
