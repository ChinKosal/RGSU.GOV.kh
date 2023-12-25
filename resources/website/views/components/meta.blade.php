@section('meta')
    <meta property="og:title" content="{{ isset($title) ? $title : '' }}" />
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ isset($image) ? $image : asset('images/logo/meta.png') }}" />
    <meta property="og:image:alt" content="@lang('website.title')">
    <meta property="og:image:width" content="640" />
    <meta property="og:image:height" content="640" />
    <meta property="og:site_name" content="@lang('website.title')">
    <meta name="keywords"
        content="@lang('website.title'),
        @isset($newsCategories)
            @foreach ($newsCategories as $category) {{ toObject($category?->name)?->{app()->getLocale()} . ',' }} @endforeach
        @endisset
    ">
    {{-- <meta property="og:description" content="{!! isset($description) ? $description : __('website.og_description') !!}"> --}}

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:site" content="@lang('website.title')">
    <meta property="twitter:creator" content="@lang('website.title')">
    <meta property="twitter:title" content="{{ isset($title) ? $title : '' }}">
    <meta property="twitter:description" content="{!! isset($description) ? $description : __('website.og_description') !!}">
    <meta property="twitter:image" content="{{ isset($image) ? $image : asset('images/logo/meta.png') }}">
    <meta property="twitter:image:alt" content="@lang('website.title')">
    <meta property="twitter:image:width" content="640">
    <meta property="twitter:image:height" content="640">
    <meta property="twitter:domain" content="{{ url()->current() }}">

    {{-- <meta name="description" content="{!! isset($description) ? $description : __('website.og_description') !!}"> --}}
    <meta name="keywords"
        content="@lang('website.title'), @isset($newsCategories)
    @foreach ($newsCategories as $category) {{ toObject($category?->name)?->{app()->getLocale()} . ',' }} @endforeach
@endisset">
    <meta name="author" content="@lang('website.title')">
@endsection
