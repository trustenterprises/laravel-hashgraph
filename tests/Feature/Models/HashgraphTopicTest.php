<?php

namespace Trustenterprises\LaravelHashgraph\Tests\Feature\Models;

use Trustenterprises\LaravelHashgraph\Models\HashgraphTopic;
use Trustenterprises\LaravelHashgraph\Tests\TestCase;

class HashgraphTopicTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        HashgraphTopic::create([
            "name" => 'test',
            "topic_id" => 'test'
        ]);

        $this->assertEquals(HashgraphTopic::count(), 1);


    }
}
