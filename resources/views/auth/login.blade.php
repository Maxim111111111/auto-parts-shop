@extends('layouts.account')

@section('title', 'Вход в аккаунт')

@section('content')
    <div class="card">
        <h1 class="title">Вход</h1>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="field">
                <label for="password">Пароль</label>
                <input id="password" name="password" type="password" required>
            </div>

            <div class="field">
                <label>
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                    Запомнить меня
                </label>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Войти</button>
                <a class="btn btn-outline" href="{{ route('register') }}">Создать аккаунт</a>
            </div>
        </form>
    </div>
@endsection
