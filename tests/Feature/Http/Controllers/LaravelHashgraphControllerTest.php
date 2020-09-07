<?php

namespace Trustenterprises\LaravelHashgraph\Tests\Feature\Http\Controllers;

use Trustenterprises\LaravelHashgraph\Tests\TestCase;

class LaravelHashgraphControllerTest extends TestCase
{
    /**
     * This should fail if there isn't a payload with a valid X-signature header
     *
     * @test
     */
    public function the_hashgraph_webhook_returns_ok()
    {
        $this->post('/hashgraph')
            ->assertOk()
            ->assertSee("ok");
    }
}
