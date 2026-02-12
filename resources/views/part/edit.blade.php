@extends('layouts.main')

@section('title', 'Редактирование запчасти')

@section('content_header_title', 'Редактирование запчасти')

@section('page_content')
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title m-0">{{ $part->name }}</h3>
        </div>

        <form method="POST" action="{{ route('part.update', $part) }}">
            @csrf
            @method('PATCH')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Название</label>
                            <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $part->name) }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input id="sku" type="text" name="sku" class="form-control" value="{{ old('sku', $part->sku) }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="brand">Бренд</label>
                            <input id="brand" type="text" name="brand" class="form-control" value="{{ old('brand', $part->brand) }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="category_id">Категория</label>
                            <select id="category_id" name="category_id" class="form-control">
                                <option value="">Без категории</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((string) old('category_id', $part->category_id) === (string) $category->id)>{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="price">Цена</label>
                            <input id="price" type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $part->price) }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="stock">Остаток</label>
                            <input id="stock" type="number" step="1" min="0" name="stock" class="form-control" value="{{ old('stock', $part->stock) }}" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $part->description) }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $part->is_active))>
                            <label class="custom-control-label" for="is_active">Позиция активна</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('part.show', $part) }}" class="btn btn-outline-secondary">Отмена</a>
                <button type="submit" class="btn btn-warning">Сохранить изменения</button>
            </div>
        </form>
    </div>
@endsection
