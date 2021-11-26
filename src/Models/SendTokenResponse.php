<?php

namespace Trustenterprises\LaravelHashgraph\Models;

class SendTokenResponse
{
    private String $amount = '';

    private String $receiver_id = '';

    private String $transaction_id = '';

    private String $error = '';

    private bool $transfer_success = false;

    /**
     * @param object $response
     */
    public function __construct(object $response)
    {
        if (property_exists($response, 'error')) {
            $this->error = $response->error;
        } else {
            $this->transfer_success = true;
            $this->amount = $response->amount;
            $this->receiver_id = $response->receiver_id;
            $this->transaction_id = $response->transaction_id;
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
     * @return String
     */
    public function getError(): string
    {
        return $this->error;
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
}
