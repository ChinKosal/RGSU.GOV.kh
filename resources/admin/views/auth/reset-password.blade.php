@extends('admin::auth.index')
@section('auth')
<form class="form" id="login-form" action="{!! route('admin-login-post') !!}" method="post">
    @csrf
    <input type="hidden" name="returnUrl" value={{ request()->returnUrl }}>
    <h2 class="title">@lang('auth.reset-password.title')</h2>
    <div class="form-wrapper">
        <div class="form-row">
            <label for="password">
                <i data-feather="key"></i>
                @lang('auth.reset-password.form.new-password.label')
            </label>
            <div class="group-input" x-data={show:false}>
                <input name="password" x-bind:type="!show ? 'password' : 'text'" placeholder="@lang('auth.reset-password.form.new-password.placeholder')"
                    error-message="@lang('auth.reset-password.form.new-password.error')" autocomplete="off">
                <div class="group-item" @click="show = !show">
                    <span x-show="!show">
                        <i data-feather="eye"></i>
                    </span>
                    <span x-show="show">
                        <i data-feather="eye-off"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-row">
            <label for="confirm_password">
                <i data-feather="key"></i>
                @lang('auth.reset-password.form.confirm-new-password.label')
            </label>
            <div class="group-input" x-data={show:false}>
                <input name="confirm_password" x-bind:type="!show ? 'password' : 'text'" placeholder="@lang('auth.reset-password.form.confirm-new-password.placeholder')"
                    error-message="@lang('auth.reset-password.form.confirm-new-password.error')" autocomplete="off">
                <div class="group-item" @click="show = !show">
                    <span x-show="!show">
                        <i data-feather="eye"></i>
                    </span>
                    <span x-show="show">
                        <i data-feather="eye-off"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="option">
            <div class="remember-me">
            </div>
            <div class="link-label">
                <span @click="$link(`{!! route('admin-login') !!}`)">@lang('auth.forgot-password.form.has-account')</span>
            </div>
        </div>
        <button color="primary" class="btn-submit-form" type="submit">
            <span>@lang('auth.forgot-password.form.button')</span>
        </button>
    </div>
    @if (Session::has('status'))
        <p class="q-label-error">
            @lang('auth.forgot-password.form.error_login')
        </p>
    @endif
</form>
@stop

@section('script')
    <script type="module">
        $(document).ready(function() {
            let validate = {
                password: {
                    required: true,
                    email: true
                },
                confirm_password: {
                    required: true,
                    email: true
                    match: "#password"
                }
            };
            $validator("#login-form", validate, {
                inputClass: "error-input",
                messageClass: "error",
                showMessage: false
            });
        });
    </script>
@stop
