<?php

namespace Trustenterprises\LaravelHashgraph\Tests\MockTraits;

use Trustenterprises\LaravelHashgraph\Models\HashgraphTopic;

trait HashgraphStaticMock
{
    public function mockWebhookPayload($topic_id = '0.0.1')
    {
        return [
            "reference" => "test",
            "topic_id" => $topic_id,
            "consensus_timestamp" => [
                "seconds" => 1598306688,
                "nanos" => 750728007,
            ],
            "transaction_id" => "0.0.116507@1598338540.979000000",
        ];
    }

    public function generateTopic($topic_id = '0.0.1')
    {
        return HashgraphTopic::create([
            'name' => 'Test',
            'topic_id' => $topic_id,
        ]);
    }
}
