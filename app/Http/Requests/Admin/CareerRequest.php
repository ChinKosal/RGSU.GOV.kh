<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
class CareerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title_km'          => 'required|string|max:255',
            'title_en'          => 'required|string|max:255',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'content_km'        => 'required',
            'content_en'        => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title_km.required'         => __('validate.attributes.title_km'),
            'title_km.max'              => __('validate.attributes.max_character', ['column' => __('validate.attributes.title_km'),  'max' => 255]),
            'title_en.required'         => __('validate.attributes.title_en'),
            'title_en.max'              => __('validate.attributes.max_character', ['column' => __('validate.attributes.title_en'),  'max' => 255]),
            'start_date.required'       => __('validate.attributes.start_date'),
            'start_date.date'           => __('validate.attributes.date', ['field' => __('validate.attributes.start_date')]),
            'end_date.required'         => __('validate.attributes.end_date'),
            'end_date.date'             => __('validate.attributes.date', ['field' => __('validate.attributes.end_date')]),
            'end_date.after_or_equal'   => __('validate.attributes.after_or_equal', ['field' => 'Start Date']),
            'content_km.required'       => __('validate.attributes.content_km'),
            'content_en.required'       => __('validate.attributes.content_en'),
        ];
    }
}
