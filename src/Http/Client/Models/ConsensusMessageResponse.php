<?php

namespace Trustenterprises\LaravelHashgraph\Http\Client\Models;

class ConsensusMessageResponse
{
    private String $reference;

    private String $topic_id;

    private String $transaction_id;

    private String $explorer_url;

    /*
     * Basic object with seconds and nanos as ints
     */
    private object $consensus_timestamp;

    /**
     * ConsensusMessageResponse constructor, using the http response contents body object
     * @param object $message_response
     */
    public function __construct(object $message_response)
    {
        $this->reference = $message_response->reference;
        $this->topic_id = $message_response->topic_id;
        $this->transaction_id = $message_response->transaction_id;
        $this->explorer_url = $message_response->explorer_url;
        $this->consensus_timestamp = $message_response->consensus_timestamp;
    }

    /**
     * @return String
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @return String
     */
    public function getTopicId(): string
    {
        return $this->topic_id;
    }

    /**
     * @return String
     */
    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }

    /**
     * @return String
     */
    public function getExplorerUrl(): string
    {
        return $this->explorer_url;
    }

    /**
     * @return object
     */
    public function getConsensusTimestamp(): object
    {
        return $this->consensus_timestamp;
    }
}
