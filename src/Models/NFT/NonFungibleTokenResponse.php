<?php

namespace Trustenterprises\LaravelHashgraph\Models\NFT;

class NonFungibleTokenResponse
{
    private String $token_id;

    private String $collection_name;

    private String $symbol;

    private String $max_supply;

    private String $treasury_id;

    private bool $collection_considered_unsafe;

    private array $errors = [];

    /**
     * ConsensusMessageResponse constructor, using the http response contents body object
     * @param object $response
     */
    public function __construct(object $response)
    {
        if (property_exists($response, 'errors')) {
            $this->errors = $response->errors;
        } else {
            $this->token_id = $response->data->token_id;
            $this->collection_name = $response->data->collection_name;
            $this->symbol = $response->data->symbol;
            $this->max_supply = $response->data->max_supply;
            $this->treasury_id = $response->data->treasury_id;
            $this->collection_considered_unsafe = $response->data->collection_considered_unsafe;
        }
    }

    /**
     * @return String
     */
    public function getTokenId(): string
    {
        return $this->token_id;
    }

    /**
     * @return String
     */
    public function getCollectionName(): string
    {
        return $this->collection_name;
    }

    /**
     * @return String
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return String
     */
    public function getMaxSupply(): string
    {
        return $this->max_supply;
    }

    /**
     * @return String
     */
    public function getTreasuryId(): string
    {
        return $this->treasury_id;
    }

    /**
     * @return bool
     */
    public function isCollectionConsideredUnsafe(): bool
    {
        return $this->collection_considered_unsafe;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isSuccessful(): bool {
        return count($this->errors) == 0;
    }
}
