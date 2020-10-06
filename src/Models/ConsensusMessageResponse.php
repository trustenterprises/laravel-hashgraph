<?php

namespace Trustenterprises\LaravelHashgraph\Models;

class ConsensusMessageResponse
{
    private ?String $reference;

    private String $topic_id;

    private String $transaction_id;

    private String $explorer_url;

    /*
     * Basic object with seconds and nanos as ints
     */
    private ?object $consensus_timestamp;

    /**
     * ConsensusMessageResponse constructor, using the http response contents body object
     * @param object $response
     */
    public function __construct(object $response)
    {
        $this->topic_id = $response->topic_id;
        $this->transaction_id = $response->transaction_id;
        $this->explorer_url = $response->explorer_url;

        $this->reference = $response->reference ?? null;
        $this->consensus_timestamp = $response->consensus_timestamp ?? null;
    }

    /**
     * @return String | null
     */
    public function getReference(): ?string
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
     * @return object | null
     */
    public function getConsensusTimestamp(): ?object
    {
        return $this->consensus_timestamp;
    }
}
