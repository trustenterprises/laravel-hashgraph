<?php

namespace Trustenterprises\LaravelHashgraph\Models;

class BequestTokenResponse
{
    private String $amount;

    private String $receiver_id;

    /**
     * ConsensusMessageResponse constructor, using the http response contents body object
     * @param object $response
     */
    public function __construct(object $response)
    {
        $this->amount = $response->amount;
        $this->receiver_id = $response->receiver_id;
    }

    /**
     * @return String
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @return String
     */
    public function getReceiverId(): string
    {
        return $this->receiver_id;
    }
}
