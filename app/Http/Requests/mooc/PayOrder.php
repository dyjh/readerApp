<?php

namespace App\Http\Requests\mooc;

use App\Http\Requests\Authorize;
use Illuminate\Foundation\Http\FormRequest;

class PayOrder extends FormRequest
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
            'platform' => 'required|in:alipay,wxpay',
            'type' => 'required|in:app,web'
        ];
    }
}
