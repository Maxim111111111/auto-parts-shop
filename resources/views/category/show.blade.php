@extends('layouts.main')

@section('title', 'Просмотр категории')

@section('content_header_title', $category->title)

@section('content_header_actions')
    <a href="{{ route('category.edit', $category) }}" class="btn btn-warning btn-sm">Изменить</a>
@endsection

@section('page_content')
    <div class="card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $category->id }}</dd>

                <dt class="col-sm-3">Название</dt>
                <dd class="col-sm-9">{{ $category->title }}</dd>

                <dt class="col-sm-3">Создана</dt>
                <dd class="col-sm-9">{{ $category->created_at?->format('Y-m-d H:i') }}</dd>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title m-0">Последние запчасти в категории</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Название</th>
                        <th>Бренд</th>
                        <th>Цена</th>
                        <th>Остаток</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($category->parts as $part)
                        <tr>
                            <td>{{ $part->sku }}</td>
                            <td><a href="{{ route('part.show', $part) }}">{{ $part->name }}</a></td>
                            <td>{{ $part->brand ?: '—' }}</td>
                            <td>{{ number_format((float) $part->price, 2, '.', ' ') }}</td>
                            <td>{{ $part->stock }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">В этой категории пока нет запчастей.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
