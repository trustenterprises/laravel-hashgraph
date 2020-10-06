<?php


namespace Trustenterprises\LaravelHashgraph\Tests;

class HashgraphConfigTest extends TestCase
{
    /**
     * @test
     */
    public function has_url_in_config()
    {
        $this->assertNotEmpty(config('hashgraph.client_url'));
    }

    /**
     * @test
     */
    public function assert_has_secret_key()
    {
        $this->assertNotEmpty(config('hashgraph.secret_key'));
    }
}
