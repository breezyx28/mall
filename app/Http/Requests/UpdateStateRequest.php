<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateStateRequest extends FormRequest
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
            'name' => 'string|max:191',
            'city' => 'string|max:191',
            'deliverTime' => 'integer',
            'deliverPrice' => 'integer'
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
            'name.string' => 'حقل الولاية يجب ان يكون نصي',
            'city.string' => 'حقل المدينة يجب ان يكون نصي',
            'deliverTime.integer' => 'حقل زمن التوصيل يجب ان يكون رقمي',
            'deliverPrice.integer' => 'حقل سعر التوصيل يجب ان يكون رقمي',
        ];
    }
}
