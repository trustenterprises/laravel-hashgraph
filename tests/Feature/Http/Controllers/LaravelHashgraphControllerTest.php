<?php

namespace Trustenterprises\LaravelHashgraph\Tests\Feature\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Trustenterprises\LaravelHashgraph\Events\ConsensusMessageWasReceived;
use Trustenterprises\LaravelHashgraph\Models\HashgraphConsensusMessage;
use Trustenterprises\LaravelHashgraph\Tests\MockTraits\HashgraphStaticMock;
use Trustenterprises\LaravelHashgraph\Tests\TestCase;
use Trustenterprises\LaravelHashgraph\Utilities\Hmac;

class LaravelHashgraphControllerTest extends TestCase
{
    use HashgraphStaticMock;

    /**
     * This should fail if there isn't a payload with a valid X-signature header
     *
     * @test
     */
    public function the_hashgraph_webhook_returns_failed()
    {
        $this->json('POST', '/hashgraph')
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertSee('Header X-Signature is required');
    }

    /**
     * @test
     */
    public function the_webhook_returns_failed_invalid_payload_for_signature()
    {
        $this->json('POST', '/hashgraph', $this->mockWebhookPayload(), [
           'x-signature' => '123',
        ])->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertSee('Signature does not match payload');
    }

    /**
     * @test
     */
    public function the_webhook_returns_failed_bad_topic()
    {
        $payload = $this->mockWebhookPayload();

        $this->json('POST', '/hashgraph', $payload, [
           'x-signature' => Hmac::generate(json_encode($payload)),
        ])->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertSee('Unable to store consensus message, topic does not exist');
    }

    /**
     * @test
     */
    public function create_a_topic_then_send_a_consensus_message_to_the_webhook()
    {
        Event::fake();

        $topic_id = "0.0.1";

        $this->assertEquals(0, HashgraphConsensusMessage::count());

        $this->generateTopic();

        $payload = $this->mockWebhookPayload($topic_id);

        $this->json('POST', '/hashgraph', $payload, [
           'x-signature' => Hmac::generate(json_encode($payload)),
        ])->assertStatus(Response::HTTP_OK);

        $this->assertEquals(1, HashgraphConsensusMessage::count());

        Event::assertDispatched(ConsensusMessageWasReceived::class, function ($event) use ($topic_id) {
            return $event->consensus_message->topic_id === $topic_id;
        });
    }
}
