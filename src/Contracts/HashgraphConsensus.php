<?php

namespace Trustenterprises\LaravelHashgraph\Contracts;

use Trustenterprises\LaravelHashgraph\Models\ConsensusMessage;
use Trustenterprises\LaravelHashgraph\Models\ConsensusMessageResponse;
use Trustenterprises\LaravelHashgraph\Models\FungibleToken;
use Trustenterprises\LaravelHashgraph\Models\FungibleTokenResponse;
use Trustenterprises\LaravelHashgraph\Models\TopicInfo;

/**
 * Interface ServerlessHashgraphInterface
 * @package Trustenterprises\LaravelHashgraph\Http\Client
 */
interface HashgraphConsensus
{
    /**
     * Get the balance in HBARs of the serverless client
     * @return String
     */
    public function getBalance(): String;

    /**
     * Get the information of a topic
     * @param String $id
     * @return TopicInfo
     */
    public function getTopicInfo(String $id): TopicInfo;

    /**
     * @param String $memo
     * @return TopicInfo
     */
    public function createTopic(String $memo): TopicInfo;


    /**
     * @param String $id
     * @param String $memo
     * @return TopicInfo
     */
    public function updateTopic(String $id, String $memo): TopicInfo;

    /**
     * @param ConsensusMessage $message
     * @return ConsensusMessageResponse
     */
    public function sendMessageConsensus(ConsensusMessage $message): ConsensusMessageResponse;

    /**
     * @param FungibleToken $token
     * @return FungibleTokenResponse
     */
    public function mintFungibleToken(FungibleToken $token): FungibleTokenResponse;
}
