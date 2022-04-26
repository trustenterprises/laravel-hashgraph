<?php

namespace Trustenterprises\LaravelHashgraph\Models;

class AccountHoldingsResponse
{

    private array $token_ids;

    private bool $has_tokens;

    /**
     * ConsensusMessageResponse constructor, using the http response contents body object
     * @param object $response
     */
    public function __construct(object $response)
    {
        $this->token_ids = $response->token_ids;
        $this->has_tokens = $response->has_tokens;
    }

    /**
     * @return array
     */
    public function getTokenIds(): array
    {
        return $this->token_ids;
    }

    /**
     * @return bool
     */
    public function hasTokens(): bool
    {
        return $this->has_tokens;
    }
}
