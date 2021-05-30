<?php

namespace App\Http\Requests;

use App\Rules\AmountRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class OrdersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->role_id == 3) {
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
            'orders' => 'required|array',
            'account_id' => 'nullable|exists:accounts,id',
            'payment_method' => ['required', 'string', Rule::in(['cash', 'credit', 'bok'])],
            'orders.*.state_id' => 'nullable|integer|exists:states,id',
            'orders.*.order_address' => 'max:191',
            'orders.*.product_id' => 'required|integer|exists:products,id',
            'orders.*.amount' => ['required', 'integer', 'min:1', new AmountRule()],
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
