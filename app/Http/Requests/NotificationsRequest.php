<?php

namespace App\Http\Requests;

use App\Helper\ResponseMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class NotificationsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->role->position == 'admin') {
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
            'title' => 'required|string|max:191',
            'content' => 'required|string|max:191',
            'user_id' => 'required_without:pulk_users|integer|exists:users,id',
            'pulk_users' => 'required_without:user_id|array',
            'pulk_users.*' => 'integer|exists:users,id',
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
            'title.required' => 'حقل العنوان مطلوب',
            'title.string' => 'حقل العنوان يجب ان يكون نص',
            'title.max' => 'حقل العنوان تجاوز طول الأحرف المسموحة',
            'content.required' => 'حقل المحتوى مطلوب',
            'content.string' => 'حقل المحتوى يجب ان يكون نص',
            'content.max' => 'حقل المحتوى تجاوز طول الأحرف المسموحة',
            'user_id.required' => 'حقل رقم  المستخدم المرجعي مطلوب',
            'user_id.integer' => 'حقل رقم المستخدم المرجعي يجب ان يكون رقم صحيح',
            'user_id.exists' => 'حقل رقم المستخدم المرجعي غير صحيح',
        ];
    }
}
