<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
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
        $id = $this->id ?? '';
        return [
            'name' => 'required',
            'username'  => 'required|unique:users,username,' . $id,
            'phone' => 'required',
            'password' => $id ? 'nullable' : 'required',
            'password_confirmation' => $id ? 'nullable' : 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validate.required', ['attribute' => __('validate.attributes.name')]),
            'username.required' => __('validate.required', ['attribute' => __('validate.attributes.username')]),
            'username.unique' => __('validate.unique', ['attribute' => __('validate.attributes.username')]),
            'phone.required' => __('validate.required', ['attribute' => __('validate.attributes.phone')]),
            'password.required' => __('validate.required', ['attribute' => __('validate.attributes.password')]),
            'password_confirmation.required' => __('validate.required', ['attribute' => __('validate.attributes.password_confirmation')]),
            'password_confirmation.same' => __('validate.same', ['attribute' => __('validate.attributes.password')]),
        ];
    }
}