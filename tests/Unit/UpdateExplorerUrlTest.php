<?php

namespace Tests\Listeners;

use Trustenterprises\LaravelHashgraph\Events\ConsensusMessageWasReceived;
use Trustenterprises\LaravelHashgraph\Listeners\UpdateExplorerUrl;
use Trustenterprises\LaravelHashgraph\Models\HashgraphConsensusMessage;
use Trustenterprises\LaravelHashgraph\Tests\MockTraits\HashgraphStaticMock;
use Trustenterprises\LaravelHashgraph\Tests\TestCase;

class UpdateExplorerUrlTest extends TestCase
{
    use HashgraphStaticMock;

    /**
     * @test
     */
    public function check_that_the_event_listener_will_fire()
    {
        $topic = $this->generateTopic();

        $message = HashgraphConsensusMessage::create([
            'explorer_url' => 'https://remotesoftwaredevelopment.com/',
            'topic_id' => $topic->topic_id,
            'transaction_id' => '123',
            'consensus_timestamp_seconds' => '0',
            'consensus_timestamp_nanos' => '0',
        ]);

        (new UpdateExplorerUrl())->handle(
            new ConsensusMessageWasReceived($message)
        );

        $this->assertEquals('https://trust.enterprises/', $message->fresh()->explorer_url);
    }
}
