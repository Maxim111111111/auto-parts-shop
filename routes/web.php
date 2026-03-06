<?php

use App\Http\Controllers\Auth\Login\CreateController as LoginCreateController;
use App\Http\Controllers\Auth\Login\StoreController as LoginStoreController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\Profile\EditController as ProfileEditController;
use App\Http\Controllers\Auth\Profile\UpdateController as ProfileUpdateController;
use App\Http\Controllers\Auth\Profile\UpdatePasswordController as ProfileUpdatePasswordController;
use App\Http\Controllers\Auth\Register\CreateController as RegisterCreateController;
use App\Http\Controllers\Auth\Register\StoreController as RegisterStoreController;
use App\Http\Controllers\Auth\StatusController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/catalog-data', \App\Http\Controllers\Storefront\CatalogController::class)->name('storefront.catalog-data');
Route::get('/catalog-data/{part}', \App\Http\Controllers\Storefront\PartDataController::class)->name('storefront.part-data');
Route::get('/auth/me', StatusController::class)->name('auth.status');
Route::post('/auth/logout', LogoutController::class)->name('auth.logout');

Route::middleware('guest')->group(function () {
    Route::get('/login', LoginCreateController::class)->name('login');
    Route::post('/login', LoginStoreController::class)->name('login.store');

    Route::get('/register', RegisterCreateController::class)->name('register');
    Route::post('/register', RegisterStoreController::class)->name('register.store');
});

Route::post('/logout', LogoutController::class)->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', ProfileEditController::class)->name('profile.edit');
    Route::patch('/profile', ProfileUpdateController::class)->name('profile.update');
    Route::put('/profile/password', ProfileUpdatePasswordController::class)->name('profile.password.update');
});

Route::get('/', \App\Http\Controllers\Storefront\IndexController::class)->name('storefront.index');
Route::get('/catalog', \App\Http\Controllers\Storefront\IndexController::class)->name('storefront.catalog');
Route::get('/category/{category}', \App\Http\Controllers\Storefront\IndexController::class)
    ->whereNumber('category')
    ->name('storefront.category');
Route::get('/product/{part}', \App\Http\Controllers\Storefront\IndexController::class)
    ->whereNumber('part')
    ->name('storefront.product');
Route::get('/cart', \App\Http\Controllers\Storefront\IndexController::class)->name('storefront.cart');

Route::middleware(['auth', EnsureAdmin::class])->group(function () {
    Route::get('/admin', \App\Http\Controllers\Main\IndexController::class)->name('main.index');

    Route::prefix('categories')->group(function () {
        Route::get('/', \App\Http\Controllers\Category\IndexController::class)->name('category.index');
        Route::get('/create', \App\Http\Controllers\Category\CreateController::class)->name('category.create');
        Route::post('/', \App\Http\Controllers\Category\StoreController::class)->name('category.store');
        Route::get('/{category}', \App\Http\Controllers\Category\ShowController::class)->name('category.show');
        Route::get('/{category}/edit', \App\Http\Controllers\Category\EditController::class)->name('category.edit');
        Route::patch('/{category}', \App\Http\Controllers\Category\UpdateController::class)->name('category.update');
        Route::delete('/{category}', \App\Http\Controllers\Category\DeleteController::class)->name('category.delete');
    });

    Route::prefix('parts')->group(function () {
        Route::get('/', \App\Http\Controllers\Part\IndexController::class)->name('part.index');
        Route::get('/create', \App\Http\Controllers\Part\CreateController::class)->name('part.create');
        Route::post('/', \App\Http\Controllers\Part\StoreController::class)->name('part.store');
        Route::get('/{part}', \App\Http\Controllers\Part\ShowController::class)->name('part.show');
        Route::get('/{part}/edit', \App\Http\Controllers\Part\EditController::class)->name('part.edit');
        Route::patch('/{part}', \App\Http\Controllers\Part\UpdateController::class)->name('part.update');
        Route::delete('/{part}', \App\Http\Controllers\Part\DeleteController::class)->name('part.delete');
    });
});
