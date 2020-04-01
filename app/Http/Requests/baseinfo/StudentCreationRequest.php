<?php

namespace App\Http\Requests\baseinfo;

use Illuminate\Foundation\Http\FormRequest;

class StudentCreationRequest extends FormRequest
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
            'name' => 'required|min:1|max:20',
            'password' => 'required|min:6',
            'school_id' => 'required|integer|exists:schools,id',
            'grade_id' => 'required|integer|exists:grades,id',
            'ban_id' => 'required|integer|exists:bans,id',
            'phone' => 'required|phone|unique:students,phone',
            'sms_code' => 'required|alpha_num|min:6|max:6'
        ];
    }
}
