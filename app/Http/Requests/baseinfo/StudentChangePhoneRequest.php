<?php

namespace App\Http\Requests\baseinfo;

use Illuminate\Foundation\Http\FormRequest;

class StudentChangePhoneRequest extends FormRequest
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
            'phone' => 'required|phone|unique:students,phone',
            'sms_code' => 'required|alpha_num|min:6|max:6'
        ];
    }

    public function messages()
    {
        return [
            'phone.unique' => '手机号码重复'
        ];
    }
}
