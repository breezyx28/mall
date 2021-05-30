<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StatesRequest extends FormRequest
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
            'name' => 'required|string|max:191',
            'city' => 'required|string|max:191',
            'deliverTime' => 'required|integer',
            'deliverPrice' => 'required|integer'
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
            'name.required' => 'حقل اسم الولاية مطلوب',
            'name.string' => 'حقل الولاية يجب ان يكون نصي',
            'city.required' => 'حقل المدينة مطلوب',
            'city.string' => 'حقل المدينة يجب ان يكون نصي',
            'deliverTime.required' => 'حقل الزمن التوصيل مطلوب',
            'deliverTime.integer' => 'حقل زمن التوصيل يجب ان يكون رقمي',
            'deliverPrice.required' => 'حقل سعر التوصيل مطلوب',
            'deliverPrice.integer' => 'حقل سعر التوصيل يجب ان يكون رقمي',
        ];
    }
}
