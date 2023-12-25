@extends('website::shared.layout')

@section('menu')
    @include('website::shared.navbar')
@stop

@section('content')
    @include('website::pages.Pmpage.pmpage')
    @include('website::components.pagination')
@stop

@section('footer')
    @include('website::shared.footer')
@stop
