<?php

namespace Trustenterprises\LaravelHashgraph\Tests;

use Trustenterprises\LaravelHashgraph\LaravelHashgraphServiceProvider;

class LaravelHashgraphTest extends TestCase
{
    /**
     * Testing the Facade
     *
     * @test
     */
    public function laravel_hashgraph_facade()
    {
        $hashgraph = app(LaravelHashgraphServiceProvider::HASHGRAPH_SERVICE_NAME);
        $hashgraph->add(5);

        error_log($hashgraph->getResult());
        $this->assertTrue(true);
    }
}
