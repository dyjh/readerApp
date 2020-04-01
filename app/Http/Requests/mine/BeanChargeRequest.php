<?php

namespace App\Http\Requests\mine;

use Illuminate\Foundation\Http\FormRequest;

class BeanChargeRequest extends FormRequest
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
            'paymethod' => 'required',
            'product_bean_id' => 'required'
        ];
    }
}
