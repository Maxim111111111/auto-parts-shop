@extends('layouts.main')

@section('title', 'Категории')

@section('content_header_title', 'Категории запчастей')

@section('content_header_actions')
    <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Добавить категорию
    </a>
@endsection

@section('page_content')
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('category.index') }}" class="row">
                <div class="col-md-6">
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Поиск по названию категории">
                </div>
                <div class="col-md-6 d-flex">
                    <button class="btn btn-primary mr-2" type="submit">Найти</button>
                    <a href="{{ route('category.index') }}" class="btn btn-outline-secondary">Сбросить</a>
                </div>
            </form>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Кол-во запчастей</th>
                        <th class="text-right">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->title }}</td>
                            <td>{{ $category->parts_count }}</td>
                            <td class="text-right">
                                <a href="{{ route('category.show', $category) }}" class="btn btn-info btn-sm">Открыть</a>
                                <a href="{{ route('category.edit', $category) }}" class="btn btn-warning btn-sm">Изменить</a>
                                <form action="{{ route('category.delete', $category) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Удалить категорию?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Категорий пока нет.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="card-footer clearfix">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
