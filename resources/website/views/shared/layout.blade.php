<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @vite(['resources/website/js/app.js', 'resources/website/sass/app.scss', 'resources/website/js/body.js', 'resources/website/js/header.js'])

</head>

<body>
    <div>
        @yield('menu')
    </div>

    <div class="content">
        @yield('content')
    </div>

    <div>
        @yield('footer')
    </div>

    @include('website::components.btntop')

</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
</script>
