@extends('layouts.main')

@section('title', 'Карточка запчасти')

@section('content_header_title', $part->name)

@section('content_header_actions')
    <a href="{{ route('part.edit', $part) }}" class="btn btn-warning btn-sm">Изменить</a>
@endsection

@section('page_content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">SKU</dt>
                        <dd class="col-sm-8">{{ $part->sku }}</dd>

                        <dt class="col-sm-4">Название</dt>
                        <dd class="col-sm-8">{{ $part->name }}</dd>

                        <dt class="col-sm-4">Категория</dt>
                        <dd class="col-sm-8">{{ $part->category?->title ?? 'Без категории' }}</dd>

                        <dt class="col-sm-4">Бренд</dt>
                        <dd class="col-sm-8">{{ $part->brand ?: '—' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Цена</dt>
                        <dd class="col-sm-8">{{ number_format((float) $part->price, 2, '.', ' ') }}</dd>

                        <dt class="col-sm-4">Остаток</dt>
                        <dd class="col-sm-8">{{ $part->stock }} шт</dd>

                        <dt class="col-sm-4">Статус</dt>
                        <dd class="col-sm-8">
                            @if ($part->is_active)
                                <span class="badge badge-success">Активна</span>
                            @else
                                <span class="badge badge-secondary">Скрыта</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Обновлено</dt>
                        <dd class="col-sm-8">{{ $part->updated_at?->format('Y-m-d H:i') }}</dd>
                    </dl>
                </div>
            </div>

            @if ($part->description)
                <hr>
                <h5>Описание</h5>
                <p class="mb-0">{{ $part->description }}</p>
            @endif
        </div>
    </div>
@endsection
