<?php

namespace App\Http\Requests;

use App\Rules\dateFormatRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UsersRequest extends FormRequest
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
            'firstName' => 'required|string|max:191',
            'username' => 'required|unique:users|string|max:191',
            'middleName' => 'nullable|string|max:191',
            'lastName' => 'nullable|string|max:191',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png',
            'phone' => 'required|unique:users,phone|digits:10',
            'email' => 'nullable|string|regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/|max:191',
            'address' => 'nullable|string|max:191',
            'state_id' => 'required|exists:states,id|integer',
            'birthDate' => 'nullable|date',
            'gender' => ['required', 'string', Rule::in(['ذكر', 'انثى'])],
            'password' => 'required|string|max:191',
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
            'firstName.required' => 'حقل الإسم الأول مطلوب',
            'firstName.string' => 'حقل الإسم الأول يجب ان يكون نص',
            'firstName.max' => 'حقل الإسم الأول تجاوز الحد المسموح لعدد الحروف',
            'username.required' => 'حقل إسم المستخدم مطلوب',
            'username.unique' => 'أسم المستخدم موجود بالفعل',
            'username.string' => 'أسم المستخدم يجب ان يكون نص',
            'username.max' => 'أسم المستخدم تجاوز الحد المسموح لعدد الحروف',
            'middleName.nullable' => 'الإسم الأوسط يقبل قيم خالية',
            'middleName.string' => 'الإسم الأوسط يجب ان يكون نصي',
            'middleName.max' => 'الإسم الأوسط تجاوز الحد المسموح لعدد الحرف',
            'lastName.nullable' => 'حقل الإسم الأخير مطلوب',
            'thumbnail.image' => 'نوع الصورة يجب ان يكون ضمن jpg , jpeg, png',
            'phone.required' => 'حقل الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'phone.digits' => 'يجب ان يكون طول رقم الهاتف 10 خانات',
            'email.string' => 'الإيميل يجب ان يكون نصي',
            'email.regex' => 'الإيميل غير مناسب',
            'address.string' => 'العنوان يجب ان يكون نصي',
            'state_id.required' => 'حقل الولاية مطلوب',
            'state_id.exists' => 'حقل الولاية غير موجود في قاعدة البيانات',
            'state_id.integer' => 'حقل الولاية يجب ان يكون رقمي',
            'birthDate.date' => 'حقل تاريخ الميلاد يجب ان يكون تاريخ حقيقي',
            'gender.required' => 'حقل النوع مطلوب',
            'gender.string' => 'حقل النوع يجب ان يكون نصي',
            'gender.in' => 'قية حقل النوع يجب ان تكون ذكر او أنثى',
            'password.required' => 'حقل كلمة السر مطلوب',
            'password.string' => 'حقل كلمة السر يجب ان يكون نص',
            'password.max' => 'حقل كلمة السر تجاوز الحد المسموح لعدد الحروف',
        ];
    }
}
