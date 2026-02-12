@extends('layouts.main')

@section('title', 'Новая запчасть')

@section('content_header_title', 'Добавление запчасти')

@section('page_content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title m-0">Карточка запчасти</h3>
        </div>

        <form method="POST" action="{{ route('part.store') }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Название</label>
                            <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input id="sku" type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="brand">Бренд</label>
                            <input id="brand" type="text" name="brand" class="form-control" value="{{ old('brand') }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="category_id">Категория</label>
                            <select id="category_id" name="category_id" class="form-control">
                                <option value="">Без категории</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="price">Цена</label>
                            <input id="price" type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', 0) }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="stock">Остаток</label>
                            <input id="stock" type="number" step="1" min="0" name="stock" class="form-control" value="{{ old('stock', 0) }}" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <textarea id="description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked(old('is_active', true))>
                            <label class="custom-control-label" for="is_active">Показывать позицию в каталоге</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('part.index') }}" class="btn btn-outline-secondary">Назад</a>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
