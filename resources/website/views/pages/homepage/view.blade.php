@extends('website::shared.layout')

@section('menu')
    @include('website::shared.navbar')
@stop

@section('content')
    @include('website::pages.homepage.cover_page')
@stop
