<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('app.title') @yield('title')</title>
    <link rel="shortcut icon" href="{!! asset('images/logo/navigator.png') !!}" type="image/x-icon">
    @vite(['resources/admin/sass/app.scss','resources/admin/js/app.js','resources/admin/js/package/iCheck/icheck.min.js'])
    @yield('style')
<body>
    @yield('index')
    @yield('script')
    @vite(['resources/admin/js/body.js'])
</body>

</html>
