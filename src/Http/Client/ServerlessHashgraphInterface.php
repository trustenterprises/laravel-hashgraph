<?php

namespace Trustenterprises\LaravelHashgraph\Http\Client;

use Trustenterprises\LaravelHashgraph\Http\Client\Models\ConsensusMessage;
use Trustenterprises\LaravelHashgraph\Http\Client\Models\ConsensusMessageResponse;
use Trustenterprises\LaravelHashgraph\Http\Client\Models\TopicInfo;

interface ServerlessHashgraphInterface
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
    public function createTopic(String $memo): TopicInfo; // Need to create models for topic


    /**
     * @param String $memo
     * @return TopicInfo
     */
    public function updateTopic(String $memo): TopicInfo;

    public function sendMessageConsensus(ConsensusMessage $message): ConsensusMessageResponse;
}
