<?php

namespace App\Http\Requests\mooc;

use App\Http\Requests\Authorize;
use Illuminate\Foundation\Http\FormRequest;

class CreateLessonComment extends FormRequest
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
            'content'    => 'max:190',
            'rate'       => 'required|numeric|between:1,5',
        ];
    }
}
