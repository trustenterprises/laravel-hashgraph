<?php

namespace Trustenterprises\LaravelHashgraph\Models\NFT;

class TransferNftResponse
{
    private String $serial_number = '';

    private String $receiver_id = '';

    private String $transaction_id = '';

    private array $errors = [];

    private bool $transfer_success = false;

    /**
     * @param object $response
     */
    public function __construct(object $response)
    {
        if (property_exists($response, 'errors')) {

            error_log(json_encode($response->errors));
            $this->errors = $response->errors;
        } else {
            $this->transfer_success = true;
            $this->serial_number = $response->data->serial_number;
            $this->transaction_id = $response->data->transaction_id;
            $this->receiver_id = $response->data->receiver_id;
            $this->transaction_id = $response->data->transaction_id;
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
     * @return String
     */
    public function getSerialNumber(): string
    {
        return $this->serial_number;
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

    public function isSuccessful(): bool {
        return count($this->errors) == 0;
    }
}
