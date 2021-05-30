<?php

namespace App\Http\Requests;

use App\Helper\ResponseMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function authorize()
    {
        if (auth()->user()->role_id == 1) {
            return true;
        }

        return ResponseMessage::Error('غير مصرح', auth()->user()->role->position);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => ['required', 'string', 'max:191', Rule::in(['accepted', 'delivered', 'rejected', 'available'])],
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
            'status.required' => 'حقل حالة الطلب مطلوب',
            'status.string' => 'حقل حالة الطلب يجب ان يكون نص',
            'status.in' => 'حقل حالة الطلب يجب ان يتضمن accepted, delivered, rejected or available',
        ];
    }
}
