<?php

namespace App\Http\Requests;

use App\Rules\dateFormatRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class updateUsersRequest extends FormRequest
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
            'firstName' => 'string|max:191',
            'userName' => 'unique:users|string|max:191',
            'middleName' => 'string|max:191',
            'LastName' => 'string|max:191',
            'thumbnail' => 'image|mimes:jpg,jpeg,png',
            'phone' => 'unique:users,phone|digits:10',
            'email' => 'string|unique:users|regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/',
            'address' => 'string|max:191',
            'birthDate' => ['date', new dateFormatRule()],
            'state_id' => 'exists:states,id|integer',
            'gender' =>  Rule::in(['ذكر', 'انثى']),
            'activity' =>  'boolean',
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
}
