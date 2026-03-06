<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('database.default') !== 'sqlite') {
            return;
        }

        $database = config('database.connections.sqlite.database');

        if (! is_string($database) || $database === ':memory:') {
            return;
        }

        $path = str_starts_with($database, DIRECTORY_SEPARATOR)
            ? $database
            : base_path($database);

        if (! File::exists($path)) {
            File::ensureDirectoryExists(dirname($path));
            File::put($path, '');
        }
    }
}
