@extends('adminlte::page')

@section('title', trim($__env->yieldContent('title')) ?: 'Админка магазина автозапчастей')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h1 class="m-0">@yield('content_header_title', 'Панель управления')</h1>
        <div>
            @yield('content_header_actions')
        </div>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="font-weight-bold mb-1">Проверьте данные формы:</div>
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('page_content')
@stop
