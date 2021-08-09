<?php

namespace Trustenterprises\LaravelHashgraph\Models;

use Trustenterprises\LaravelHashgraph\Utilities\ConvertToProofId;

class BequestTokenResponse
{
    private String $amount;

    private String $receiver_id;

    private String $transaction_id;

    /**
     * ConsensusMessageResponse constructor, using the http response contents body object
     * @param object $response
     */
    public function __construct(object $response)
    {
        $this->amount = $response->amount;
        $this->receiver_id = $response->receiver_id;
        $this->transaction_id = $response->transaction_id;
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
    public function convertTransactionIdForProof(): string
    {
        return ConvertToProofId::create($this->transaction_id);
    }
}
