<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'name'                      => 'required',
            'username'                  => 'required|unique:users,username,' . $id,
            'email'                     => $this->email ? 'string|email|unique:users,email,'. $id : '',
            'phone'                     => $this->phone ? 'required|unique:users,phone,'. $id : '',
            'verification_code'         => $id ? 'nullable' : 'required|numeric|digits:6',
            'password'                  => $id ? 'nullable' : 'required|string|max:125',
            'password_confirmation'     => $id ? 'nullable' : 'required|string|same:password',
            'profile'                   => $this->profile || $this->tmp_profile ? 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' : 'nullable',
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
            'name.required'                     => __('website.form.placeholder.name'),
            'username.required'                 => __('website.form.placeholder.username'),
            'username.unique'                   => __('website.form.placeholder.username_unique'),
            'email.required'                    => __('website.form.placeholder.email'),
            'email.unique'                      => __('website.form.placeholder.email_unique'),
            'phone.required'                    => __('website.form.placeholder.phone'),
            'phone.unique'                      => __('website.form.placeholder.phone_unique'),
            'verification_code.required'        => __('website.form.placeholder.verification_code'),
            'verification_code.numeric'         => __('website.form.placeholder.verification_code'),
            'verification_code.digits'          => __('website.form.placeholder.verification_code'),
            'password.required'                 => __('website.form.placeholder.password'),
            'password_confirmation.required'    => __('website.form.placeholder.confirm_password'),
            'password_confirmation.same'        => __('website.form.placeholder.confirm_password_same'),
            'avatar.image'                      => __('website.form.placeholder.avatar_image'),
            'avatar.mimes'                      => __('website.form.placeholder.avatar_mimes'),
            'avatar.max'                        => __('website.form.placeholder.avatar_max'),
        ];
    }
}
