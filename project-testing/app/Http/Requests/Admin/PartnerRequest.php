<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
class PartnerRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'logo'          => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'             => __('validate.attributes.name'),
            'name.string'               => __('validate.attributes.name'),
            'name.max'                  => __('validate.attributes.max_character', ['column' => __('validate.attributes.name'), 'max' => 255]),
            'logo.required'             => 'Logo is required',
        ];
    }
}
