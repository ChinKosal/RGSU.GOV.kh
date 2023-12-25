<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('website.title') @yield('title')</title>
    @yield('meta')
    <link rel="shortcut icon" href="{{ asset('images/logo/navigator.png') }}" type="image/x-icon">
    @vite(['resources/website/sass/app.scss', 'resources/website/js/app.js', 'resources/website/js/header.js'])
</head>

<body>

    @yield('index')
    @yield('script')

    @vite(['resources/website/js/body.js'])
</body>

</html>
