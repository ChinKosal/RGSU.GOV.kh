@extends('admin::auth.index')
@section('auth')
    <form class="form" id="login-form" action="{!! route('admin-login-post') !!}" method="post">
        @csrf
        <input type="hidden" name="returnUrl" value={{ request()->returnUrl }}>
        <h2 class="title">@lang('auth.forgot-password.title')</h2>
        <div class="form-wrapper">
            <div class="form-row">
                <label for="email">
                    <i data-feather="user"></i>
                    @lang('auth.forgot-password.form.email.label')
                </label>
                <input name="email" placeholder="@lang('auth.forgot-password.form.email.placeholder')" type="text" error-message="@lang('auth.forgot-password.form.email.error')"
                    autocomplete="off">
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
                email: {
                    required: true,
                    email: true
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
