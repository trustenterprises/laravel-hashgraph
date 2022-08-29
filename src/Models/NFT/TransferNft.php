<?php

namespace Trustenterprises\LaravelHashgraph\Models\NFT;

use GuzzleHttp\RequestOptions;

class TransferNft
{
    private String $token_id;

    private String $receiver_id;

    private int $serial;

    /**
     * SendToken constructor.
     *
     * @param String $token_id
     * @param String $receiver_id
     * @param int $serial
     */
    public function __construct(string $token_id, string $receiver_id, int $serial)
    {
        $this->token_id = $token_id;
        $this->receiver_id = $receiver_id;
        $this->serial = $serial;
    }

    public function forRequest(): array
    {
        return [
            RequestOptions::JSON => [
                'token_id' => $this->getTokenId(),
                'receiver_id' => $this->getReceiverId(),
                'serial_number' => $this->getSerial(),
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
    public function getSerial(): int
    {
        return $this->serial;
    }
}
