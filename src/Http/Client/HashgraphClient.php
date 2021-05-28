<?php


namespace Trustenterprises\LaravelHashgraph\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Trustenterprises\LaravelHashgraph\Contracts\HashgraphConsensus;
use Trustenterprises\LaravelHashgraph\Models\ConsensusMessage;
use Trustenterprises\LaravelHashgraph\Models\ConsensusMessageResponse;
use Trustenterprises\LaravelHashgraph\Models\FungibleToken;
use Trustenterprises\LaravelHashgraph\Models\FungibleTokenResponse;
use Trustenterprises\LaravelHashgraph\Models\TopicInfo;

/**
 * Class ServerlessHashgraphClient
 * @package Trustenterprises\LaravelHashgraph\Http\Client
 */
class HashgraphClient implements HashgraphConsensus
{
    private Client $guzzle;

    /**
     * ServerlessHashgraphClient constructor.
     * @param Client $guzzle
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public static function createAuthorisedInstance() : HashgraphConsensus
    {
        $guzzle = GuzzleWrapper::getAuthenticatedGuzzleInstance();

        return new self($guzzle);
    }

    /**
     * @return string
     * @throws GuzzleException
     */
    public function getBalance(): string
    {
        $response = $this->guzzle->get('api/account/balance');
        $body = json_decode($response->getBody()->getContents());

        return $body->data->balance;
    }

    /**
     * @param string $id
     * @return TopicInfo
     * @throws GuzzleException
     */
    public function getTopicInfo(string $id): TopicInfo
    {
        $response = $this->guzzle->get('api/consensus/topic/' . $id);
        $data = json_decode($response->getBody()->getContents())->data;

        return new TopicInfo($data->topicMemo, $id);
    }

    /**
     * @param string $memo
     * @return TopicInfo
     * @throws GuzzleException
     */
    public function createTopic(string $memo): TopicInfo
    {
        $response = $this->guzzle->post('api/consensus/topic', [
            'json' => [
                'memo' => $memo,
                'enable_private_submit_key' => true,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents())->data;

        return new TopicInfo($data->memo, $data->topic);
    }

    /**
     * @param string $id
     * @param string $memo
     * @return TopicInfo
     * @throws GuzzleException
     */
    public function updateTopic(string $id, string $memo): TopicInfo
    {
        $response = $this->guzzle->put('api/consensus/topic/' . $id, [
            'json' => [
                'memo' => $memo,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents())->data;

        return new TopicInfo($data->memo, $data->topic_id);
    }

    /**
     * @param ConsensusMessage $message
     * @return ConsensusMessageResponse
     * @throws GuzzleException
     */
    public function sendMessageConsensus(ConsensusMessage $message): ConsensusMessageResponse
    {
        $response = $this->guzzle->post('api/consensus/message', [
            'json' => $message->forMessageRequest(),
        ]);

        $data = json_decode($response->getBody()->getContents())->data;

        return new ConsensusMessageResponse($data);
    }

    /**
     * @param FungibleToken $token
     * @return FungibleTokenResponse
     * @throws GuzzleException
     */
    public function mintFungibleToken(FungibleToken $token): FungibleTokenResponse
    {
        $response = $this->guzzle->post('api/token', [
            'json' => $token->forTokenRequest(),
        ]);

        $data = json_decode($response->getBody()->getContents())->data;

        return new FungibleTokenResponse($data);
    }
}
