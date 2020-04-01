<?php

namespace App\Http\Requests\platform;

use Illuminate\Foundation\Http\FormRequest;

class WhiteBoardCreationRequest extends FormRequest
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
            'lesson_chapter_id' => 'required|exists:lesson_chapters,id',
            'name' => 'required|string',
            'limit' => 'required|integer',
            'mode' => 'required|string'
        ];
    }
}
