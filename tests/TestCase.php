<?php

namespace Trustenterprises\LaravelHashgraph\Tests;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Trustenterprises\LaravelHashgraph\LaravelHashgraphServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/database/factories');

        Route::hashgraph('hashgraph');
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelHashgraphServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        include_once __DIR__.'/../database/migrations/create_laravel_hashgraph_tables.php.stub';
        (new \CreateLaravelHashgraphTables())->up();

        // Setup the environment and load for tests

        $app->useEnvironmentPath(__DIR__.'/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);

        parent::getEnvironmentSetUp($app);

        $app['config']->set('hashgraph.client_url', env('HASHGRAPH_NODE_URL'));
        $app['config']->set('hashgraph.secret_key', env('HASHGRAPH_SECRET_KEY'));
    }
}
