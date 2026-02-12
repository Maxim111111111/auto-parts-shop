@extends('layouts.main')

@section('title', 'Запчасти')

@section('content_header_title', 'Склад запчастей')

@section('content_header_actions')
    <a href="{{ route('part.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Добавить запчасть
    </a>
@endsection

@section('page_content')
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('part.index') }}" class="row">
                <div class="col-md-4 mb-2">
                    <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Поиск: название, SKU, бренд">
                </div>
                <div class="col-md-3 mb-2">
                    <select name="category_id" class="form-control">
                        <option value="">Все категории</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="status" class="form-control">
                        <option value="">Любой статус</option>
                        <option value="active" @selected($status === 'active')>Только активные</option>
                        <option value="inactive" @selected($status === 'inactive')>Только неактивные</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2 d-flex">
                    <button class="btn btn-primary w-100" type="submit">Фильтр</button>
                </div>
            </form>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>SKU</th>
                        <th>Название</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Остаток</th>
                        <th>Статус</th>
                        <th class="text-right">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parts as $part)
                        <tr>
                            <td>{{ $part->id }}</td>
                            <td>{{ $part->sku }}</td>
                            <td>{{ $part->name }}</td>
                            <td>{{ $part->category?->title ?? 'Без категории' }}</td>
                            <td>{{ number_format((float) $part->price, 2, '.', ' ') }}</td>
                            <td>{{ $part->stock }}</td>
                            <td>
                                @if ($part->is_active)
                                    <span class="badge badge-success">Активна</span>
                                @else
                                    <span class="badge badge-secondary">Скрыта</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('part.show', $part) }}" class="btn btn-info btn-sm">Открыть</a>
                                <a href="{{ route('part.edit', $part) }}" class="btn btn-warning btn-sm">Изменить</a>
                                <form action="{{ route('part.delete', $part) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Удалить позицию?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Позиции не найдены.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($parts->hasPages())
            <div class="card-footer clearfix">
                {{ $parts->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
