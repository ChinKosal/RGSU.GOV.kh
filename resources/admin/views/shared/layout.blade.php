@extends('admin::index')
@section('index')

    <div class="container">
        <div class="container-wrapper">
            <div class="sidebar">
                @include('admin::shared.sidebar')
            </div>
            <div class="content" x-data="{}">
                @yield('layout')
                @include('admin::components.confirm-dialog')
                @include('admin::components.toast')
                @include('admin::components.select-option')
                @include('admin::file-manager.popup')
                @include('admin::components.add-map')
            </div>
        </div>
    </div>
@stop
