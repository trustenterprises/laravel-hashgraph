<?php

namespace Trustenterprises\LaravelHashgraph\Models;

class AccountTokenBalanceResponse
{

    private String $token_id;

    private int $amount;

    private int $decimals;

    private String $raw_amount;

    /**
     * ConsensusMessageResponse constructor, using the http response contents body object
     * @param object $response
     */
    public function __construct(object $response)
    {
        $this->token_id = $response->token_id;
        $this->amount = $response->amount;
        $this->decimals = $response->decimals;
        $this->raw_amount = $response->raw_amount;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return String
     */
    public function getTokenId(): string
    {
        return $this->token_id;
    }

    /**
     * @return int
     */
    public function getDecimals(): int
    {
        return $this->decimals;
    }

    /**
     * @return String
     */
    public function getRawAmount(): string
    {
        return $this->raw_amount;
    }
}
