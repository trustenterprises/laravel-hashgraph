<?php

namespace Trustenterprises\LaravelHashgraph\Models\NFT;

use GuzzleHttp\RequestOptions;

class NftMetadata
{
    private array $metadata;

    public function __construct(array $metadata)
    {
        $this->metadata = $metadata;
    }

    public function forRequest(): array
    {
        return [
            RequestOptions::JSON => $this->getMetadata()
        ];
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }
}
