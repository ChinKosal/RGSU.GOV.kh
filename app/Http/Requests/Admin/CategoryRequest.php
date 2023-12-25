<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
class CategoryRequest extends FormRequest
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
            'name_km'   => 'required|string|max:255',
            'name_en'   => 'required|string|max:255',
            'ordering'  => 'required',
            'status'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name_km.required'  => __('validate.attributes.name_km'),
            'name_km.max'       => __('validate.attributes.max_character', ['column' => __('validate.attributes.name_km'),  'max' => 255]),
            'name_en.required'  => __('validate.attributes.name_en'),
            'name_en.max'       => __('validate.attributes.max_character', ['column' => __('validate.attributes.name_en'),  'max' => 255]),
            'ordering.required' => __('validate.attributes.ordering'),
            'status.required'   => __('validate.attributes.status'),
        ];
    }
}
