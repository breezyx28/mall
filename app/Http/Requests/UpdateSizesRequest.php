<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSizesRequest extends FormRequest
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
            'unit' => 'string|max:191',
            'size' => 'string|max:191',
            'measure' => 'string|max:191',
            'measureType' => 'string|max:4',
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
            'unit.string' => 'حقل وحدة القياس يجب ان يكون نص',
            'unit.max' => 'حقل وحدة القياس تجاوز الطول المسموح به',
            'size.string' => 'حقل الحجم يجب ان يكون نص',
            'size.max' => 'حقل الحجم تجاوز الطول المسموح به',
            'measure.string' => 'حقل المقاس يجب ان يكون نص',
            'measure.max' => 'حقل المقاس تجاوز الطول المسموح به',
            'measureType.string' => 'حقل نوع المقاس يجب ان يكون نص',
            'measureType.max' => 'حقل المقاس تجاوز الطول المسموح به وهو ال4',
        ];
    }
}
