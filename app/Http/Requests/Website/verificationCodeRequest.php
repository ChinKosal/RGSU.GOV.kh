<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class verificationCodeRequest extends FormRequest
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
        if (request('type') == 'register') {
            return [
                'phone'      => request('method') == 'phone' ? 'required|unique:users,phone' : '',
                'email'      => request('method') == 'email' ? 'required|unique:users,email' : '',
            ];
        }else{
            return [
                'phone'      => request('method') == 'phone' ? 'required|exists:users,phone' : '',
                'email'      => request('method') == 'email' ? 'required|exists:users,email' : '',
            ];
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages()
    {
        return [
            'phone.required'     => __('website.form.placeholder.phone'),
            'phone.unique'       => __('website.form.placeholder.phone_unique'),
            'phone.exists'       => __('website.form.placeholder.phone_exists'),
            'email.required'     => __('website.form.placeholder.email'),
            'email.unique'       => __('website.form.placeholder.email_unique'),
            'email.exists'       => __('website.form.placeholder.email_exists'),
        ];
    }
}