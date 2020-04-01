<?php

namespace App\Http\Requests\store;

use App\Http\Requests\Authorize;
use Illuminate\Foundation\Http\FormRequest;

class ProductBookRequest extends FormRequest
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
            'category'    => 'exists:product_categories,id',
            'name'        => 'string',
            'price_order' => 'sometimes|in:up,down',
            'sales_order' => 'sometimes|in:up,down',
         //   'new_order'   => 'sometimes|in:up,down',
            'search'      => 'sometimes|min:1',
        ];
    }
}
