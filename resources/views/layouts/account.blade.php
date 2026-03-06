<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Аккаунт')</title>
    <style>
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f6f8fb; color: #111827; }
        .container { width: min(760px, calc(100% - 2rem)); margin: 2rem auto; }
        .topbar { display: flex; gap: .75rem; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
        .links { display: flex; gap: .5rem; align-items: center; flex-wrap: wrap; }
        .link, .btn-link { border: 1px solid #dbe3ef; background: #fff; color: #111827; text-decoration: none; border-radius: 10px; padding: .5rem .75rem; font-size: .9rem; }
        .btn-link { cursor: pointer; }
        .card { background: #fff; border: 1px solid #e5eaf2; border-radius: 16px; padding: 1.25rem; box-shadow: 0 10px 24px rgba(17, 24, 39, 0.06); }
        .title { margin: 0 0 1rem; font-size: 1.5rem; }
        .field { margin-bottom: 1rem; }
        .field label { display: block; margin-bottom: .35rem; font-size: .9rem; color: #4b5563; }
        .field input { width: 100%; box-sizing: border-box; border: 1px solid #cfd7e6; border-radius: 10px; padding: .65rem .75rem; font-size: 1rem; }
        .actions { display: flex; gap: .5rem; align-items: center; margin-top: 1rem; }
        .btn { border: 0; border-radius: 10px; padding: .65rem .95rem; font-size: .95rem; cursor: pointer; }
        .btn-primary { background: #2b4fff; color: #fff; }
        .btn-outline { background: #fff; color: #111827; border: 1px solid #dbe3ef; text-decoration: none; }
        .alert { border-radius: 10px; padding: .75rem .85rem; margin-bottom: 1rem; font-size: .92rem; }
        .alert-success { background: #eaf8ef; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .errors { margin: 0; padding-left: 1rem; }
        .muted { color: #6b7280; font-size: .9rem; }
        .split { display: grid; gap: 1rem; }
        @media (min-width: 900px) { .split { grid-template-columns: 1fr 1fr; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <a class="link" href="{{ route('storefront.index') }}">На витрину</a>
            <div class="links">
                @auth
                    <a class="link" href="{{ route('profile.edit') }}">Профиль</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-link">Выйти</button>
                    </form>
                @else
                    <a class="link" href="{{ route('login') }}">Войти</a>
                    <a class="link" href="{{ route('register') }}">Регистрация</a>
                @endauth
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul class="errors">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
