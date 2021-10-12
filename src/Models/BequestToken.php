<?php

namespace Trustenterprises\LaravelHashgraph\Models;

class BequestToken
{
    private String $encrypted_receiver_key;

    private String $token_id;

    private String $receiver_id;

    private String $amount;

    /**
     * BequestToken constructor.
     * @param String $encrypted_receiver_key
     * @param String $token_id
     * @param String $receiver_id
     * @param String $amount
     */
    public function __construct(string $encrypted_receiver_key, string $token_id, string $receiver_id, string $amount)
    {
        $this->encrypted_receiver_key = $encrypted_receiver_key;
        $this->token_id = $token_id;
        $this->receiver_id = $receiver_id;
        $this->amount = $amount;
    }

    public function forRequest(): array
    {
        return [
            'encrypted_receiver_key' => $this->getEncryptedReceiverKey(),
            'token_id' => $this->getTokenId(),
            'receiver_id' => $this->getReceiverId(),
            'amount' => $this->getAmount(),
        ];
    }

    /**
     * @return string
     */
    public function getEncryptedReceiverKey(): string
    {
        return $this->encrypted_receiver_key;
    }

    /**
     * @return string
     */
    public function getTokenId(): string
    {
        return $this->token_id;
    }

    /**
     * @return string
     */
    public function getReceiverId(): string
    {
        return $this->receiver_id;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }
}
