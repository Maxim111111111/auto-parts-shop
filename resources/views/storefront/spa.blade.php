<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Auto Parts Shop</title>
    @vite(['resources/css/storefront.css', 'resources/js/storefront/spa.js'])
</head>
<body>
    <div id="storefront-app">
        <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:Inter,Segoe UI,sans-serif;color:#111827;">
            Загрузка витрины...
        </div>
    </div>
    <noscript>
        <div style="padding:20px;font-family:Inter,Segoe UI,sans-serif;">
            Для работы сайта включите JavaScript в браузере.
        </div>
    </noscript>
</body>
</html>
