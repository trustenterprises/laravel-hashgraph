<?php

namespace Trustenterprises\LaravelHashgraph\Models\NFT;

class MintToken
{
    private String $token_id;

    private String $cid;

    private ?Int $amount;

    /**
     * @param String $token_id
     * @param String $cid
     * @param ?Int $amount
     */
    public function __construct(String $token_id, String $cid, ?Int $amount)
    {
        $this->token_id = $token_id;
        $this->cid = $cid;
        $this->amount = $amount;
    }

    public function forRequest(): array
    {
        return [
            'cid' => $this->getCid(),
            'amount' => $this->getAmount()
        ];
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
    public function getCid(): string
    {
        return $this->cid;
    }

    /**
     * @return Int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }
}
