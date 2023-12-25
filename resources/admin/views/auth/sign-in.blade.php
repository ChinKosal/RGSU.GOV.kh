@extends('admin::auth.index')
@section('auth')
    <form class="form" x-data="loginForm" action="{!! route('admin-login-post') !!}" method="post">
        @csrf
        <input type="hidden" name="returnUrl" value={{ request()->returnUrl }}>
        <h2 class="title p-5">@lang('auth.sign-in.title')</h2>
        <div class="form-wrapper">
            <div class="form-row">
                <label for="username">
                    <i data-feather="user"></i>
                    @lang('auth.sign-in.form.username.label')
                </label>
                <input name="username" placeholder="@lang('auth.sign-in.form.username.placeholder')" type="text" error-message="@lang('auth.sign-in.form.username.error')"
                    autocomplete="off" value="{{ old('username') }}">

                @if ($errors->has('username'))
                    <small class="text-danger text-left">
                        {{ $errors->first('username') }}
                    </small>                    
                @endif
            </div>
            <div class="form-row">
                <label for="password">
                    <i data-feather="key"></i>
                    @lang('auth.sign-in.form.password.label')
                </label>
                <div class="group-input" x-data={show:false}>
                    <input name="password" x-bind:type="!show ? 'password' : 'text'" placeholder="@lang('auth.sign-in.form.password.placeholder')"
                        error-message="@lang('auth.sign-in.form.password.error')" autocomplete="off">
                    <div class="group-item" @click="show = !show">
                        <span x-show="!show">
                            <i data-feather="eye"></i>
                        </span>
                        <span x-show="show">
                            <i data-feather="eye-off"></i>
                        </span>
                    </div>
                </div>

                @if ($errors->has('password'))
                    <small class="text-danger text-left">
                        {{ $errors->first('password') }}
                    </small>
                @endif
            </div>
            <div class="option">
                <div class="remember-me">
                    <input name="remember" id="remember" type="checkbox" value="1">
                    <label for="remember">@lang('auth.sign-in.form.remember')</label>
                </div>
            </div>
            <button color="primary" class="btn-submit-form text-white" type="submit">
                <span>@lang('auth.sign-in.form.button')</span>
            </button>
        </div>
        @if (Session::has('status'))
            <p class="q-label-error">
                @lang('auth.sign-in.form.error_login')
            </p>
        @endif
    </form>
@stop
@section('script')
    <script type="module">
        Alpine.data('loginForm', () => ({
            init() {
                // console.log("loginForm");
            }
        }));
    </script>
@stop
