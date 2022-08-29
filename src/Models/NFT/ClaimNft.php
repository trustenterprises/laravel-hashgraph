<?php

namespace Trustenterprises\LaravelHashgraph\Models\NFT;

use GuzzleHttp\RequestOptions;

class ClaimNft
{
    private String $token_id;

    private String $receiver_id;

    private String $nft_pass_token_id;

    private Int $serial_number;

    /**
     * ClaimNft constructor.
     * @param String $token_id
     * @param String $receiver_id
     * @param String $nft_pass_token_id
     * @param Int $serial_number
     */
    public function __construct(string $token_id, string $receiver_id, string $nft_pass_token_id, int $serial_number)
    {
        $this->token_id = $token_id;
        $this->receiver_id = $receiver_id;
        $this->nft_pass_token_id = $nft_pass_token_id;
        $this->serial_number = $serial_number;
    }

    public function forRequest(): array
    {
        return [
            RequestOptions::JSON => [
                'token_id' => $this->getTokenId(),
                'receiver_id' => $this->getReceiverId(),
                'nft_pass_token_id' => $this->getNftPassTokenId(),
                'serial_number' => $this->getSerialNumber()
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
     * @return String
     */
    public function getNftPassTokenId(): string
    {
        return $this->nft_pass_token_id;
    }

    /**
     * @return int
     */
    public function getSerialNumber(): int
    {
        return $this->serial_number;
    }
}
