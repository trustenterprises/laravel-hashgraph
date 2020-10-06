<?php

namespace Trustenterprises\LaravelHashgraph\Tests;

use Illuminate\Support\Facades\Event;
use Trustenterprises\LaravelHashgraph\Events\TopicWasCreated;
use Trustenterprises\LaravelHashgraph\Exception\HashgraphException;
use Trustenterprises\LaravelHashgraph\LaravelHashgraph;
use Trustenterprises\LaravelHashgraph\Models\ConsensusMessage;
use Trustenterprises\LaravelHashgraph\Models\HashgraphTopic;
use Trustenterprises\LaravelHashgraph\Models\TopicInfo;

class LaravelHashgraphTest extends TestCase
{
    const LOW_BALANCE = 100;
    const TOPIC_NAME = 'laravel_hashgraph_test';

    /**
     * @test
     */
    public function check_that_a_balance_is_returned_and_valid()
    {
        $balance = LaravelHashgraph::balance();

        $this->assertIsString($balance);
        $this->assertTrue((int) $balance > static::LOW_BALANCE);
    }

    /**
     * @test
     */
    public function check_that_no_topic_has_been_created()
    {
        $topic = LaravelHashgraph::withTopic(self::TOPIC_NAME)->getInfo();

        $this->assertEquals(TopicInfo::EMPTY_TOPIC_ID, $topic->getTopicId());
    }

    /**
     * This is a longer test suite to handle the life cycle of topic creation and message submission
     * big test as the SQLlite refreshes for every test. Grumble.
     *
     * @test
     */
    public function e2e_check_testnet_topics_and_messages()
    {
        Event::fake();

        $hashgraph_request = LaravelHashgraph::withTopic(self::TOPIC_NAME);

        // This creates a topic on hedera testnet
        $topicinfo = $hashgraph_request->createTopic();

        Event::assertDispatched(TopicWasCreated::class, function ($event) {
            return $event->topic->name === self::TOPIC_NAME;
        });

        $this->assertNotEquals(TopicInfo::EMPTY_TOPIC_ID, $topicinfo->getTopicId());

        // This checks that a topic has been saved
        $topic = HashgraphTopic::fromName(self::TOPIC_NAME)->first();

        $this->assertEquals(self::TOPIC_NAME, $topic->name);

        // Check that a topic can be updated
        $updated_topic = $hashgraph_request->updateTopic('memo');
        $this->assertEquals('memo', $updated_topic->getMemo());

        // Wait for topic to be updated
        sleep(3);

        // Check that a topic can be read
        $read_topic = $hashgraph_request->getInfo();
        $this->assertEquals('memo', $read_topic->getMemo());
        $this->assertEquals($topicinfo->getTopicId(), $read_topic->getTopicId());
    }

    /**
     * This is a longer test suite to handle the life cycle message submission, with automated topic creation.
     *
     * @test
     */
    public function e2e_check_message_creation()
    {
        // As the DB is refreshed this requires a new topic to be created on the fly.
        $hashgraph_request = LaravelHashgraph::withTopic(self::TOPIC_NAME);

        $message_response = $hashgraph_request->sendMessage(
            new ConsensusMessage('hello laravel-hashgraph test')
        );

        $topic = HashgraphTopic::fromName(self::TOPIC_NAME)->first();

        $this->assertEquals($topic->topic_id, $message_response->getTopicId());
        $this->assertNull($message_response->getReference());
        $this->assertNull($message_response->getConsensusTimestamp());

        // This tests the response of a synchronous message
        $reference = 'laravel-hashgraph-test';

        $message = new ConsensusMessage('hello synchronous message');
        $message->setReference($reference);
        $message->setAllowSynchronousConsensus(true);

        $sync_response = $hashgraph_request->sendMessage($message);

        $this->assertEquals($reference, $sync_response->getReference());
        $this->assertNotNull($sync_response->getConsensusTimestamp());
    }

    /**
     * @test
     */
    public function check_that_a_bad_topic_cannot_be_duplicated()
    {
        HashgraphTopic::create([
           'name' => self::TOPIC_NAME,
           'topic_id' => '0',
        ]);

        $this->expectException(HashgraphException::class);

        LaravelHashgraph::withTopic(self::TOPIC_NAME)->createTopic();
    }

    /**
     * @test
     */
    public function check_that_a_bad_topic_cannot_be_updated()
    {
        $this->expectException(HashgraphException::class);

        LaravelHashgraph::withTopic(self::TOPIC_NAME)->updateTopic('');
    }
}
