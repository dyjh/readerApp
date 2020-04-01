<?php

namespace App\Http\Requests\store;

use App\Http\Requests\Authorize;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductComment extends FormRequest
{
    use Authorize;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content'               => 'max:190',
            'desc_match_rate'       => 'required|numeric|between:1,5',
            'service_attitude_rate' => 'required|numeric|between:1,5',
        ];
    }
}
