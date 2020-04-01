<?php

namespace App\Http\Requests\shares;

use Illuminate\Foundation\Http\FormRequest;

class RentBookRequest extends FormRequest
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
            'shared_book_id' => 'required|exists:private_books,shared_book_id',
            'owner_id' => 'required|exists:private_books,student_id'
        ];
    }
}
