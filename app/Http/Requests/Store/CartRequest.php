<?php

namespace App\Http\Requests\store;

use App\Http\Requests\Authorize;
use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
            'product_book_id'  => 'required|exists:product_books,id',
            'product_count'    => 'required|integer|min:1',
        ];
    }
}
