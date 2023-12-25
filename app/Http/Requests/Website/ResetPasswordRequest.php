<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
        $id = auth()->guard('web')->user()->id ?? '';
        return [
            'phone'                     => !$id && request('phone')  ? 'required':'nullable',
            'email'                     => !$id && request('email')  ? 'required':'nullable',
            'verification_code'         => $id ? 'nullable':'required',
            'password'                  => 'required',
            'password_confirmation'     => 'required|same:password',
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
            'phone.required'                    => __('website.form.placeholder.phone'),
            'email.required'                    => __('website.form.placeholder.email'),
            'verification_code.required'        => __('website.form.placeholder.verification_code'),
            'password.required'                 => __('website.form.placeholder.password'),
            'password_confirmation.required'    => __('website.form.placeholder.confirm_password'),
            'password_confirmation.same'        => __('website.form.placeholder.confirm_password_same'),
        ];
    }
}
