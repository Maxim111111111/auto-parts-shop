@extends('layouts.account')

@section('title', 'Профиль')

@section('content')
    <div class="split">
        <section class="card">
            <h1 class="title">Профиль</h1>
            <p class="muted">Обновите имя и email аккаунта.</p>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="field">
                    <label for="name">Имя</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Сохранить профиль</button>
                </div>
            </form>
        </section>

        <section class="card">
            <h2 class="title">Сменить пароль</h2>
            <p class="muted">Введите текущий пароль и задайте новый.</p>

            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="current_password">Текущий пароль</label>
                    <input id="current_password" name="current_password" type="password" required>
                </div>

                <div class="field">
                    <label for="password">Новый пароль</label>
                    <input id="password" name="password" type="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Подтверждение нового пароля</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Обновить пароль</button>
                </div>
            </form>
        </section>
    </div>
@endsection
