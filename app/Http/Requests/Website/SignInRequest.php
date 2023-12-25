<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'phone'     => request('method') == 'phone' ? 'required' : '',
            'email'     => request('method') == 'email' ? 'required' : '',
            'password'  => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages()
    {
        return [
            'phone.required'        => __('website.form.placeholder.phone'),
            'email.required'        => __('website.form.placeholder.email'),
            'password.required'     => __('website.form.placeholder.password'),
        ];
    }
}
