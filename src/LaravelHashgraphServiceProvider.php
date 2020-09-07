<?php

namespace Trustenterprises\LaravelHashgraph;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Trustenterprises\LaravelHashgraph\Http\Controllers\LaravelHashgraphWebhookController;

class LaravelHashgraphServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-hashgraph.php' => config_path('laravel-hashgraph.php'),
            ], 'config');

//            $this->publishes([
//                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/laravel-hashgraph'),
//            ], 'views');

            $migrationFileName = 'create_laravel_hashgraph_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            // $this->commands([
            //     LaravelHashgraphCommand::class,
            // ]);
        }

        Route::macro('hashgraph', function (string $prefix) {
            Route::prefix($prefix)->group(function () {
                Route::post('/', LaravelHashgraphWebhookController::class);
            });
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-hashgraph.php', 'laravel-hashgraph');
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
