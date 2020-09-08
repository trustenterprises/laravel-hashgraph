<?php


namespace Trustenterprises\LaravelHashgraph\Tests;

//use Trustenterprises\LaravelHashgraph\LaravelHashgraph;

class LaravelHashgraphTest extends TestCase
{
    /**
     * Testing the Facade
     *
     * @test
     */
    public function laravel_hashgraph_facade()
    {
        LaravelHashgraph::add(4);

        error_log(LaravelHashgraph::getResult());
        $this->assertTrue(true);
    }
}
