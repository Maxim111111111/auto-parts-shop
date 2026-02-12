@extends('layouts.main')

@section('title', 'Дашборд автозапчастей')

@section('content_header_title', 'Дашборд магазина автозапчастей')

@section('content_header_actions')
    <a href="{{ route('part.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> Добавить запчасть
    </a>
@endsection

@section('page_content')
    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $partCount }}</h3>
                    <p>Всего запчастей</p>
                </div>
                <div class="icon"><i class="fas fa-cogs"></i></div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $activePartCount }}</h3>
                    <p>Активных позиций</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $categoryCount }}</h3>
                    <p>Категорий</p>
                </div>
                <div class="icon"><i class="fas fa-tags"></i></div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalStock }}</h3>
                    <p>Остаток на складе (шт)</p>
                </div>
                <div class="icon"><i class="fas fa-warehouse"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0">Последние добавленные запчасти</h3>
                    <a href="{{ route('part.index') }}" class="btn btn-outline-primary btn-sm">Все позиции</a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Название</th>
                                <th>Категория</th>
                                <th>Цена</th>
                                <th>Остаток</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestParts as $part)
                                <tr>
                                    <td>{{ $part->sku }}</td>
                                    <td>
                                        <a href="{{ route('part.show', $part) }}">{{ $part->name }}</a>
                                    </td>
                                    <td>{{ $part->category?->title ?? 'Без категории' }}</td>
                                    <td>{{ number_format((float) $part->price, 2, '.', ' ') }}</td>
                                    <td>{{ $part->stock }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Пока нет запчастей. Добавьте первую позицию.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title m-0">Низкий остаток (<= 5 шт)</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse ($lowStockParts as $part)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('part.show', $part) }}">{{ $part->name }}</a>
                                <span class="badge badge-danger">{{ $part->stock }} шт</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Проблем с остатками нет.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title m-0">Быстрые действия</h3>
                </div>
                <div class="card-body d-flex flex-wrap">
                    <a href="{{ route('part.create') }}" class="btn btn-primary btn-sm mr-2 mb-2">Новая запчасть</a>
                    <a href="{{ route('category.create') }}" class="btn btn-outline-primary btn-sm mr-2 mb-2">Новая категория</a>
                    <a href="{{ route('part.index', ['status' => 'inactive']) }}" class="btn btn-outline-secondary btn-sm mb-2">Неактивные</a>
                </div>
            </div>
        </div>
    </div>
@endsection
