<?php

namespace App\Http\Requests\baseinfo;

use Illuminate\Foundation\Http\FormRequest;

class StudentPasswordForgetRequest extends FormRequest
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
            'phone' => 'required|phone|exists:students,phone',
            'sms_code' => 'required|alpha_num|min:6|max:6',
            'password' => 'required|min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|min:6'
        ];
    }

    public function messages()
    {
        return [
            'phone.exists' => '用户不存在'
        ];
    }
}
