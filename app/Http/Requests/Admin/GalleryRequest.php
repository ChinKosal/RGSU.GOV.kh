<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
class GalleryRequest extends FormRequest
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
            'gallery'       => 'required',
            'status'        => 'required',
        ];
    }

    public function messages()
    {
        return [
            'gallery.required'          => __('validate.attributes.gallery'),
            'status.required'           => __('validate.attributes.status'),
        ];
    }
}
