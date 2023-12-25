<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
class DocumentRequest extends FormRequest
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
            'category'          => 'required',
            'file'              => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title_km.required'         => __('validate.attributes.title_km'),
            'title_km.max'              => __('validate.attributes.max_character', ['column' => __('validate.attributes.title_km'),  'max' => 255]),
            'title_en.required'         => __('validate.attributes.title_en'),
            'title_en.max'              => __('validate.attributes.max_character', ['column' => __('validate.attributes.title_en'),  'max' => 255]),
            'category.required'         => __('validate.attributes.category'),
            'file.required'             => __('validate.attributes.file'),
        ];
    }
}
