<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class PageRequest extends FormRequest
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
            'content_km'    => request('page') != 'privacy-policy' ? 'required' : '',
            'content'       => request('page') == 'privacy-policy' ? 'required' : '',
        ];
    }
    public function messages()
    {
        return [
            'content_km.required'   => __('validate.attributes.content_km'),
            'content.required'      => __('validate.attributes.content'),
        ];
    }
}
