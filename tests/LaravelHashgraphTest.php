<?php

namespace Trustenterprises\LaravelHashgraph\Tests;

use Illuminate\Support\Facades\Event;
use Trustenterprises\LaravelHashgraph\Events\TopicWasCreated;
use Trustenterprises\LaravelHashgraph\Exception\HashgraphException;
use Trustenterprises\LaravelHashgraph\LaravelHashgraph;
use Trustenterprises\LaravelHashgraph\Models\BequestToken;
use Trustenterprises\LaravelHashgraph\Models\SendToken;
use Trustenterprises\LaravelHashgraph\Models\ConsensusMessage;
use Trustenterprises\LaravelHashgraph\Models\FungibleToken;
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
        $this->assertNotNull($message_response->getTransactionId());

        // This tests the response of a synchronous message
        $reference = 'laravel-hashgraph-test';

        $message = new ConsensusMessage('hello synchronous message');
        $message->setReference($reference);
        $message->setAllowSynchronousConsensus(true);

        $sync_response = $hashgraph_request->sendMessage($message);

        $this->assertEquals($reference, $sync_response->getReference());
        $this->assertNotNull($sync_response->getTransactionId());
    }

    /**
     * Mint a fungible token and return a token_id
     *
     * @test
     */
    public function e2e_mint_fungible_token()
    {
        $token = new FungibleToken("e2e", "e2e test token", "10", "This is a memo");

        $hashgraph_token = LaravelHashgraph::mintFungibleToken($token);

        $this->assertNotNull($hashgraph_token->getTokenId());
    }

    /**
     * Mint a fungible token and return a token_id
     *
     * @test
     */
    public function e2e_create_account_and_bequest()
    {
        // Create an account
        $account = LaravelHashgraph::createAccount();

        // Create a token
        $token = new FungibleToken("e2e", "e2e bequest token test", "10", "e2e bequest token test");
        $hashgraph_token = LaravelHashgraph::mintFungibleToken($token);

        // Send the bequest to a user
        $bequest_token = new BequestToken($account->getEncryptedKey(), $hashgraph_token->getTokenId(), $account->getAccountId(), 1);
        $bequest_response = LaravelHashgraph::bequestToken($bequest_token);

        $this->assertNotNull($bequest_response->getAmount());
        $this->assertNotNull($bequest_response->getReceiverId());
        $this->assertNotNull($bequest_response->getTransactionId());
    }

    /**
     * Mint a fungible token with decimals and check correct balance
     *
     * @test
     */
    public function e2e_create_decimal_token_send_to_account()
    {
        // Use decimals for token and transfer
        $decimals = 2;

        // Create an account
        $account = LaravelHashgraph::createAccount();

        // Create a token
        $token = new FungibleToken("e2e", "e2e decimal token test", "10", "e2e bequest token test");

        // Ensure decimals
        $token->setDecimals($decimals);

        $hashgraph_token = LaravelHashgraph::mintFungibleToken($token);

        // Send the bequest to a user
        $bequest_token = new BequestToken($account->getEncryptedKey(), $hashgraph_token->getTokenId(), $account->getAccountId(), 1);

        // Ensure decimal bequest
        $bequest_token->setDecimals($decimals);

        $bequest_response = LaravelHashgraph::bequestToken($bequest_token);

        $this->assertEquals(1, (int) $bequest_response->getAmount());
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

    /**
     * TODO: These are a set of "dangerous" tests that relies on the node being connected to Hedera testnet and usinga venly wallet.
     *
     * Checking a wallet balance.
     *
     * @test
     */
    public function check_that_a_venly_balance_is_found()
    {
        $account_id = '0.0.13283221';
        $token_id = '0.0.15647345';

        $token_balance = LaravelHashgraph::getTokenBalance($account_id, $token_id);

        $this->assertIsNumeric($token_balance->getAmount());
        $this->assertIsNumeric($token_balance->getDecimals());
        $this->assertIsString($token_balance->getTokenId());
        $this->assertIsString($token_balance->getRawAmount());
    }

    /**
     * Sending a token to a venlu wallet. This test will fail one day. 🤪
     *
     * @test
     */
    public function send_tokens_to_a_venly_wallet()
    {
        // Pre-generated Venly wallet (testnet) (maxes out at 25 assocs)
        $account_id = '0.0.15657776'; // This venly account has these tokens already
        $token_id = '0.0.15657534'; // Token already created
        $amount = 0.000001;

        $send_token = new SendToken($token_id, $account_id, $amount);

        $token_sent = LaravelHashgraph::sendToken($send_token);

        $this->assertTrue($token_sent->hasTransferSucceeded());

        // Expect the updated balance of tokens
        $token_balance = LaravelHashgraph::getTokenBalance($account_id, $token_id);

        $this->assertNotEmpty($token_balance->getRawAmount());

        $token_holdings = LaravelHashgraph::checkTokenHoldings($account_id, $token_id);

        $this->assertTrue($token_holdings->hasTokens());
    }

    /**
     * Attempt to send a token to a full-free-association venlu wallet
     *
     * @test
     */
    public function failed_send_tokens_to_a_full_venly_wallet()
    {
        // Create a token
        $token = new FungibleToken("TEST-NON-ASSOC", "Send token to non-assoc ", "10", "non-assoc transfer test");
        $hashgraph_token = LaravelHashgraph::mintFungibleToken($token);

        // Pre-generated Venly wallet (testnet) (maxes out at 25 assocs)
        $account_id = '0.0.13283221'; // This venly account has maxed out auto association

        // Send token object
        $send_token = new SendToken($hashgraph_token->getTokenId(), $account_id, 2);

        $token_sent = LaravelHashgraph::sendToken($send_token);

        $this->assertFalse($token_sent->hasTransferSucceeded());
    }

}
