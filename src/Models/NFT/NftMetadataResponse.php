<?php

namespace Trustenterprises\LaravelHashgraph\Models\NFT;

class NftMetadataResponse
{
    private String $cid;

    private array $errors = [];

    private object $meta;

    /**
     * ConsensusMessageResponse constructor, using the http response contents body object
     * @param object $response
     */
    public function __construct(object $response)
    {
        if (property_exists($response, 'errors')) {
            $this->errors = $response->errors;
            $this->meta = $response->meta;
        } else {
            $this->cid = $response->data->cid;
        }
    }

    /**
     * @return String
     */
    public function getCid(): string
    {
        return $this->cid;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return object
     */
    public function getErrorMeta(): object
    {
        return $this->meta;
    }

    public function isSuccessful(): bool {
        return count($this->errors) == 0;
    }
}
