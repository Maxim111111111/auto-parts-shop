@extends('layouts.account')

@section('title', 'Регистрация')

@section('content')
    <div class="card">
        <h1 class="title">Регистрация</h1>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="field">
                <label for="name">Имя</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
            </div>

            <div class="field">
                <label for="password">Пароль</label>
                <input id="password" name="password" type="password" required>
            </div>

            <div class="field">
                <label for="password_confirmation">Подтверждение пароля</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                <a class="btn btn-outline" href="{{ route('login') }}">У меня уже есть аккаунт</a>
            </div>
        </form>
    </div>
@endsection
