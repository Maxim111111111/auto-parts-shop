@extends('layouts.main')

@section('title', 'Новая категория')

@section('content_header_title', 'Создание категории')

@section('page_content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-0">Данные категории</h3>
        </div>
        <form action="{{ route('category.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="title">Название</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('category.index') }}" class="btn btn-outline-secondary">Назад</a>
                <button type="submit" class="btn btn-primary">Создать</button>
            </div>
        </form>
    </div>
@endsection
