<?php

namespace App\Http\Requests\baseinfo;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdatingRequest extends FormRequest
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
            'avatar' => 'nullable',
            'school_id' => 'required|integer|exists:schools,id',
            'grade_id' => 'required|integer|exists:grades,id',
            'ban_id' => 'required|integer|exists:bans,id',
        ];
    }

//    public function messages()
//    {
//        return [
//            'phone.unique' => '用户已存在',
//            'ban_id.exists' => '班级不存在',
//            'grade_id.exists' => '年级不存在',
//            'school_id.exists' => '学校不存在'
//        ];
//    }
}
