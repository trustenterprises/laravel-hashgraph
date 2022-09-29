<?php

namespace Trustenterprises\LaravelHashgraph\Models;

class BequestToken
{
    private String $encrypted_receiver_key;

    private String $token_id;

    private String $receiver_id;

    private String $amount;

    private ?int $decimals = null;

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
        $payload = [
            'encrypted_receiver_key' => $this->getEncryptedReceiverKey(),
            'token_id' => $this->getTokenId(),
            'receiver_id' => $this->getReceiverId(),
            'amount' => $this->getAmount(),
        ];

        if ($this->getDecimals()) {
            $payload['decimals'] = $this->getDecimals();
        }

        return $payload;
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

    /**
     * @return int|null
     */
    public function getDecimals(): ?int
    {
        return $this->decimals;
    }

    /**
     * @param int|null $decimals
     */
    public function setDecimals(?int $decimals): void
    {
        $this->decimals = $decimals;
    }
}
