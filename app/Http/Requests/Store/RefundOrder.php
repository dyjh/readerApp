<?php

namespace App\Http\Requests\store;

use App\Http\Requests\Authorize;
use Illuminate\Foundation\Http\FormRequest;

class RefundOrder extends FormRequest
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
            'items'                => 'required|array|min:1',
            'items.*'              => 'required|integer',
            'refund_method'        => 'required|integer|in:1,2',
            'refund_reason'        => 'required|string|max:190',
            'refund_remark'        => 'max:190',
        ];
    }

}
