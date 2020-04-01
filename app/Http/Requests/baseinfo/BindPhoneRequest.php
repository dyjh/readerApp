<?php

namespace App\Http\Requests\baseinfo;

use Illuminate\Foundation\Http\FormRequest;

class BindPhoneRequest extends FormRequest
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
            'phone' => 'required|regex:/^1[34578][0-9]{9}$/'
        ];
    }
}
