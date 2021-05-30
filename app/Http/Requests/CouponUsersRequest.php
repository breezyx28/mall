<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponUsersRequest extends FormRequest
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
            //
        ];
    }

    // protected function failedValidation(Validator $validator)
    // {
    //     $errors = $validator->errors();
    //     $messages = [];
    //     foreach ($errors->all() as $message) {
    //         $messages[] = $message;
    //     }
    //     throw new HttpResponseException(response()->json(['success' => false, 'errors' => $messages], 200));
    // }
}
