<?php

namespace App\Http\Requests;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateCourseRequest extends FormRequest
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
            'title' => [
                'required',
                'max:100',
                Rule::unique('courses')->ignore($this->course),
            ],
            'abbreviation' => [
                'required',
                'min:4',
                'max:10',
                Rule::unique('courses')->ignore($this->course),
            ],
            'description' => 'required|max:400',
            'credits' => [
                'required',
                Rule::in(['1', '2', '3', '4']),
            ],
            'semester.*' => [
                'required',
                Rule::in(['1', '2', '3']),
            ],
            'prerequisites.*' => [
                'nullable',
                Rule::in($this->getCourseIDs()),
            ],
            'concurrents.*' => [
                'nullable',
                Rule::in($this->getCourseIDs()),
            ],
        ];
    }

    /**
     * Get all course IDs
     *
     * @return array
     */
    private function getCourseIDs()
    {
        return Course::all()->pluck('id')->toArray();
    }
}
