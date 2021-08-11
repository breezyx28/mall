<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->role_id == 2) {

            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'price' => 'required|integer',
            'photo' => 'required|image|mimes:jpg,jpeg,png',
            'description' => 'string',
            'note' => 'string',
            'discount' => 'nullable|integer|max:100|min:0',
            'bar_code' => 'nullable|string|max:191',
            'category_id' => 'required|integer|exists:categories,id',
            'store_id' => 'required|integer|exists:stores,id',
            'offerText' => 'string|max:191',
            'inventory' => 'integer',
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
            'name.required' => 'إسم المنتج مطلوب',
            'name.string' => 'إسم المنتج يجب ان يكون نص',
            'name.max' => 'إسم المنتج تجواز الحد الأقصى',
            'price.required' => 'السعر مطلوب',
            'price.integer' => 'السعر يجب ان يكون رقمي',
            'photo.required' => 'الصورة مطلوبة',
            'photo.image' => 'الصورة يجب ان تكون من نوع صورة',
            'photo.mimes' => 'الصورة يحب ان تكون ضمن ال jpg png jpeg',
            'description.string' => 'وصف المنتج يجب ان يكون نص',
            'note.string' => 'الملاحظة يجب ان تكون نص',
            'discount.integer' => 'التخفيض يجب ان يكون رقمي',
            'discount.max' => 'التخفيض تجاوز الحد المسموح 100',
            'discount.min' => 'التخفيض اقل من الحد المسموح 0',
            'bar_code.string' => 'الباركود يجب ان تكون نص',
            'bar_code.max' => 'الباركود تجاوز الحد المسموح 100',
            'category_id.required' => 'الصنف مطلوب',
            'category_id.exists' => 'الصنف غير حقيقي مطلوب',
            'store_id.required' => 'المتجر مطلوب',
            'store_id.exists' => 'المتجر غير حقيقي',
            'offerText.string' => 'العرض يجب ان يكون نص',
            'offerText.max' => 'العرض تجاوز الحد المسموح للكتابة',
            'inventory.integer' => 'المخزون يجب ان يكون رقمي',
        ];
    }
}
