<?php

namespace Trustenterprises\LaravelHashgraph;

use GuzzleHttp\Exception\GuzzleException;
use Trustenterprises\LaravelHashgraph\Contracts\HashgraphConsensus;
use Trustenterprises\LaravelHashgraph\Events\TopicWasCreated;
use Trustenterprises\LaravelHashgraph\Exception\HashgraphException;
use Trustenterprises\LaravelHashgraph\Http\Client\HashgraphClient;
use Trustenterprises\LaravelHashgraph\Models\AccountCreateResponse;
use Trustenterprises\LaravelHashgraph\Models\AccountHoldingsResponse;
use Trustenterprises\LaravelHashgraph\Models\AccountTokenBalanceResponse;
use Trustenterprises\LaravelHashgraph\Models\BequestToken;
use Trustenterprises\LaravelHashgraph\Models\BequestTokenResponse;
use Trustenterprises\LaravelHashgraph\Models\NFT\BatchTransferNft;
use Trustenterprises\LaravelHashgraph\Models\NFT\BatchTransferNftResponse;
use Trustenterprises\LaravelHashgraph\Models\NFT\ClaimNft;
use Trustenterprises\LaravelHashgraph\Models\NFT\ClaimNftResponse;
use Trustenterprises\LaravelHashgraph\Models\NFT\MintToken;
use Trustenterprises\LaravelHashgraph\Models\NFT\MintTokenResponse;
use Trustenterprises\LaravelHashgraph\Models\NFT\NftMetadata;
use Trustenterprises\LaravelHashgraph\Models\NFT\NftMetadataResponse;
use Trustenterprises\LaravelHashgraph\Models\NFT\NonFungibleToken;
use Trustenterprises\LaravelHashgraph\Models\NFT\NonFungibleTokenResponse;
use Trustenterprises\LaravelHashgraph\Models\NFT\TransferNft;
use Trustenterprises\LaravelHashgraph\Models\NFT\TransferNftResponse;
use Trustenterprises\LaravelHashgraph\Models\SendTokenResponse;
use Trustenterprises\LaravelHashgraph\Models\SendToken;
use Trustenterprises\LaravelHashgraph\Models\ConsensusMessage;
use Trustenterprises\LaravelHashgraph\Models\ConsensusMessageResponse;
use Trustenterprises\LaravelHashgraph\Models\FungibleToken;
use Trustenterprises\LaravelHashgraph\Models\FungibleTokenResponse;
use Trustenterprises\LaravelHashgraph\Models\HashgraphTopic;
use Trustenterprises\LaravelHashgraph\Models\TopicInfo;

class LaravelHashgraph
{
    /**
     * Topic Name of stored topic reference.
     *
     * @var string
     */
    private string $topic_name;

    private HashgraphConsensus $client;

    public function __construct(HashgraphConsensus $hashgraph_client)
    {
        $this->client = $hashgraph_client;
    }

    /**
     * Default behaviour for creating a client which connects a Guzzle instance with configuration
     *
     * @return static
     */
    private static function withAuthenticatedClient() : self
    {
        return new self(HashgraphClient::createAuthorisedInstance());
    }

    /**
     * @return String
     * @throws GuzzleException
     */
    public static function balance() : String
    {
        return static::withAuthenticatedClient()->getClient()->getBalance();
    }

    /**
     * Set the topic key for the transactions.
     *
     * @param string $topic_name
     * @return static
     */
    public static function withTopic(string $topic_name) : self
    {
        return static::withAuthenticatedClient()->setTopic($topic_name);
    }

    /**
     * Set the topic key for the transactions.
     *
     * @param FungibleToken $token
     * @return FungibleTokenResponse
     * @throws GuzzleException
     */
    public static function mintFungibleToken(FungibleToken $token) : FungibleTokenResponse
    {
        return static::withAuthenticatedClient()->getClient()->mintFungibleToken($token);
    }

    /**
     * Create a NFT from a model
     *
     * @throws GuzzleException
     */
    public static function createNonFungibleToken(NonFungibleToken $token) : NonFungibleTokenResponse
    {
        return static::withAuthenticatedClient()->getClient()->createNft($token);
    }

    /**
     * Mint a NFT for a collection
     *
     * @throws GuzzleException
     */
    public static function mintNonFungibleToken(MintToken $token) : MintTokenResponse
    {
        return static::withAuthenticatedClient()->getClient()->mintNft($token->getTokenId(), $token);
    }

    /**
     * Generate metadata
     *
     * @throws GuzzleException
     */
    public static function generateMetadataForNft(NftMetadata $metadata) : NftMetadataResponse
    {
        return static::withAuthenticatedClient()->getClient()->createMetadata($metadata);
    }

    /**
     * Transfer an NFT to a user
     *
     * @throws GuzzleException
     */
    public static function transferNonFungibleToken(TransferNft $transferNft) : TransferNftResponse
    {
        return static::withAuthenticatedClient()->getClient()->transferNft($transferNft);
    }

    /**
     * Batch transfer an NFT to a user
     *
     * @throws GuzzleException
     */
    public static function batchTransferNonFungibleToken(BatchTransferNft $batchTransferNft) : BatchTransferNftResponse
    {
        return static::withAuthenticatedClient()->getClient()->batchTransferNft($batchTransferNft);
    }

    /**
     * A user can claim an NFT if they hold the correct pass
     *
     * @throws GuzzleException
     */
    public static function claimNonFungibleToken(ClaimNft $claimNft) : ClaimNftResponse
    {
        return static::withAuthenticatedClient()->getClient()->claimNft($claimNft);
    }

    /**
     * Set the topic key for the transactions.
     *
     * @param BequestToken $token
     * @return BequestTokenResponse
     */
    public static function bequestToken(BequestToken $token) : BequestTokenResponse
    {
        return static::withAuthenticatedClient()->getClient()->bequestToken($token);
    }

    /**
     * Create a new account for a user
     *
     * @return AccountCreateResponse
     */
    public static function createAccount() : AccountCreateResponse
    {
        return static::withAuthenticatedClient()->getClient()->createAccount();
    }

    public static function getTokenBalance(string $account_id, string $token_id, ?int $decimals = null) : AccountTokenBalanceResponse
    {
        return static::withAuthenticatedClient()->getClient()->getTokenBalance($account_id, $token_id, $decimals);
    }

    public static function checkTokenHoldings(string $account_id, string $token_id) : AccountHoldingsResponse
    {
        return static::withAuthenticatedClient()->getClient()->checkTokenHoldings($account_id, $token_id);
    }

    public static function sendToken(SendToken $sendToken) : SendTokenResponse
    {
        return static::withAuthenticatedClient()->getClient()->sendToken($sendToken);
    }

    /**
     * @return TopicInfo
     * @throws GuzzleException
     */
    public function getInfo(): TopicInfo
    {
        $topic = HashgraphTopic::fromName($this->getTopicName())->first();

        if (! $topic) {
            return TopicInfo::emptyTopic();
        }

        return $this->getClient()->getTopicInfo($topic->topic_id);
    }

    /**
     * @param string|null $memo
     * @return TopicInfo
     * @throws HashgraphException
     * @throws GuzzleException
     */
    public function createTopic(?string $memo = null): TopicInfo
    {
        $name = $this->getTopicName();
        $topic = HashgraphTopic::fromName($name)->first();

        if ($topic) {
            throw new HashgraphException('The topic with name ' . $name . ' has already been created.');
        }

        $new_topic = $this->getClient()->createTopic($name);

        $topic = HashgraphTopic::create([
           'name' => $name,
           'topic_id' => $new_topic->getTopicId(),
        ]);

        event(new TopicWasCreated($topic));

        return $new_topic;
    }

    /**
     * @param string $memo
     * @return TopicInfo
     * @throws HashgraphException|GuzzleException
     */
    public function updateTopic(string $memo): TopicInfo
    {
        $name = $this->getTopicName();
        $topic = HashgraphTopic::fromName($name)->first();

        if (! $topic) {
            throw new HashgraphException('The topic with name ' . $name . ' does not exist');
        }

        return $this->getClient()->updateTopic($topic->topic_id, $memo);
    }


    /**
     * @param ConsensusMessage $message
     * @return ConsensusMessageResponse
     * @throws GuzzleException
     * @throws HashgraphException
     */
    public function sendMessage(ConsensusMessage $message): ConsensusMessageResponse
    {
        $name = $this->getTopicName();
        $topic = HashgraphTopic::fromName($name)->first();

        if (! $topic) {
            $this->createTopic();
            sleep(3); // Do we need this?

            return $this->sendMessage($message);
        }

        $message->setTopicId($topic->topic_id);

        return $this->getClient()->sendMessageConsensus($message);
    }

    /**
     * @return string
     */
    public function getTopicName(): string
    {
        return $this->topic_name;
    }

    /**
     * @param string $topic_name
     * @return self
     */
    public function setTopic(string $topic_name): self
    {
        $this->topic_name = $topic_name;

        return $this;
    }

    /**
     * @return HashgraphClient
     */
    public function getClient(): HashgraphConsensus
    {
        return $this->client;
    }
}
