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
            'firstName' => 'nullable|string|max:191',
            'userName' => 'nullable|unique:users|string|max:191',
            'middleName' => 'nullable|string|max:191',
            'LastName' => 'nullable|string|max:191',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png',
            'phone' => 'nullable|unique:users,phone|digits:10',
            'email' => 'nullable|string|unique:users|regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/',
            'address' => 'nullable|string|max:191',
            'birthDate' => ['nullable', 'date', new dateFormatRule()],
            'state_id' => 'nullable|exists:states,id|integer',
            'gender' =>  ['nullable', Rule::in(['ذكر', 'انثى'])],
            'activity' =>  'nullable|boolean',
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
