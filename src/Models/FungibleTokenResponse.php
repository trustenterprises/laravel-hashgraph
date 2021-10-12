<?php

namespace Trustenterprises\LaravelHashgraph\Models;

class FungibleTokenResponse
{
    private String $token_id;

    /**
     * ConsensusMessageResponse constructor, using the http response contents body object
     * @param object $response
     */
    public function __construct(object $response)
    {
        $this->token_id = $response->tokenId;
    }

    /**
     * @return String
     */
    public function getTokenId(): string
    {
        return $this->token_id;
    }
}
