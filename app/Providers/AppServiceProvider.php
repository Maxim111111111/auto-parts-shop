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
        $sqliteDefault = config('database.default') === 'sqlite';

        // Guard against misconfigured production env where sqlite is selected
        // but the app expects MySQL/PostgreSQL-backed sessions/cache/queue.
        if ($sqliteDefault) {
            if (config('session.driver') === 'database') {
                config(['session.driver' => 'file']);
            }

            if (config('cache.default') === 'database') {
                config(['cache.default' => 'file']);
            }

            if (config('queue.default') === 'database') {
                config(['queue.default' => 'sync']);
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
}
