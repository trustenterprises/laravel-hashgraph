<?php

namespace Trustenterprises\LaravelHashgraph\Models\NFT;

class BatchTransferNftResponse
{
    private array $results;

    private int $expected;

    private int $actual_sent;

    private array $errors = [];

    private bool $transfer_success = false;

    /**
     * @param object $response
     */
    public function __construct(object $response)
    {
        if (property_exists($response->data, 'errors')) {
            $this->errors = $response->data->errors;
        } else {
            $this->transfer_success = true;
            $this->results = $response->data->results;
            $this->expected = $response->data->expected;
            $this->actual_sent = $response->data->actual_sent;
        }
    }

    /**
     * @return bool
     */
    public function hasTransferSucceeded(): bool
    {
        return $this->transfer_success;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @return int
     */
    public function getExpected(): int
    {
        return $this->expected;
    }

    /**
     * @return int
     */
    public function getActualSent(): int
    {
        return $this->actual_sent;
    }

    /**
     * @return bool
     */
    public function isTransferSuccess(): bool
    {
        return $this->transfer_success;
    }

    public function isSuccessful(): bool {
        return count($this->errors) == 0;
    }
}
