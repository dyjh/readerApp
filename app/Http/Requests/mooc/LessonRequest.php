<?php

namespace App\Http\Requests\mooc;

use App\Http\Requests\Authorize;
use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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
            'category'    => 'exists:lesson_categories,id',
            'grade'       => 'exists:grades,id',
            'semester'    => 'exists:semesters,id',
        //    'name'        => 'string',
            'streamed'    => 'sometimes|in:1,0',
            'tag'         => 'sometimes|in:1,2',
            'search'      => 'sometimes|min:1',
        ];
    }
}
