<?php

namespace Trustenterprises\LaravelHashgraph;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Trustenterprises\LaravelHashgraph\Commands\LaravelHashgraphCommand;
use Trustenterprises\LaravelHashgraph\Http\Controllers\LaravelHashgraphWebhookController;

class LaravelHashgraphServiceProvider extends ServiceProvider
{
    const HASHGRAPH_SERVICE_NAME = 'hashgraph_trust';

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/hashgraph.php' => config_path('hashgraph.php'),
            ], 'config');

            $migrationFileName = 'create_laravel_hashgraph_tables.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            $this->commands([
                 LaravelHashgraphCommand::class,
             ]);
        }

        Route::macro('hashgraph', function (string $prefix = 'hashgraph') {
            Route::prefix($prefix)->group(function () {
                Route::post('/', LaravelHashgraphWebhookController::class);
            });
        });

        Route::hashgraph();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/hashgraph.php', 'laravel-hashgraph');
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
