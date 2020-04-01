<?php

namespace App\Http\Requests\shares;

use Illuminate\Foundation\Http\FormRequest;

class UploadPrivateBookRequest extends FormRequest
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
            'isbn' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'isbn.required' => 'ISBN书号必传'
        ];
    }
}
