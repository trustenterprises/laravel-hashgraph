<?php

namespace Trustenterprises\LaravelHashgraph\Models\NFT;

class MintTokenResponse
{
    private String $token_id;

    private array $serial_numbers;

    private String $amount;

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
            $this->amount = $response->data->amount;
            $this->serial_numbers = $response->data->minted_serial_numbers;
            $this->token_id = $response->data->token_id;
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return String
     */
    public function getTokenId(): string
    {
        return $this->token_id;
    }

    /**
     * @return array
     */
    public function getSerialNumbers(): array
    {
        return $this->serial_numbers;
    }

    /**
     * @return String
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    public function isSuccessful(): bool {
        return count($this->errors) == 0;
    }
}
