<?php

namespace Trustenterprises\LaravelHashgraph\Models\NFT;

use GuzzleHttp\RequestOptions;

class BatchTransferNft
{
    private String $token_id;

    private String $receiver_id;

    private int $amount;

    /**
     * SendToken constructor.
     *
     * @param String $token_id
     * @param String $receiver_id
     * @param int $amount
     */
    public function __construct(string $token_id, string $receiver_id, int $amount)
    {
        $this->token_id = $token_id;
        $this->receiver_id = $receiver_id;
        $this->amount = $amount;
    }

    public function forRequest(): array
    {
        return [
            RequestOptions::JSON => [
                'token_id' => $this->getTokenId(),
                'receiver_id' => $this->getReceiverId(),
                'amount' => $this->getAmount(),
            ]
        ];
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
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }
}
