@extends('layouts.main')

@section('title', 'Редактирование категории')

@section('content_header_title', 'Редактирование категории')

@section('page_content')
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title m-0">{{ $category->title }}</h3>
        </div>
        <form action="{{ route('category.update', $category) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="card-body">
                <div class="form-group">
                    <label for="title">Название</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $category->title) }}" required>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('category.show', $category) }}" class="btn btn-outline-secondary">Отмена</a>
                <button type="submit" class="btn btn-warning">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
