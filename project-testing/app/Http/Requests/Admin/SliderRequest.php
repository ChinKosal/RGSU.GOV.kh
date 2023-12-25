<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
            'thumbnail'     => 'required',
            'content_en'    => 'required',
            'content_km'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'thumbnail.required' => __('validate.attributes.thumbnail'),
            'content_en.required' => __('validate.attributes.content_en'),
            'content_km.required' => __('validate.attributes.content_km'),
        ];
    }
}
