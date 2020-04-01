<?php

namespace App\Http\Requests\store;

use App\Http\Requests\Authorize;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrder extends FormRequest
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
            'items'                 => 'required',
//            'items'                 => 'required|array|min:1',
//            'items.*.id'            => 'required|integer',
//            'items.*.product_count' => 'required|integer|min:1',
            'province'              => 'required|string|max:50',
            'city'                  => 'required|string|max:50',
            'district'              => 'required|string|max:50',
        //    'address'               => 'required|string|max:190',
            'contact_name'          => 'required|string|max:20',
            'contact_number'        => 'required|string|max:20',
        ];
    }

}
