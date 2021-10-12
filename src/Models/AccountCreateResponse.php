<?php

namespace Trustenterprises\LaravelHashgraph\Models;

class AccountCreateResponse
{
    private String $account_id;

    private String $encrypted_key;

    private String $public_key;

    /**
     * ConsensusMessageResponse constructor, using the http response contents body object
     * @param object $response
     */
    public function __construct(object $response)
    {
        $this->account_id = $response->accountId;
        $this->encrypted_key = $response->encryptedKey;
        $this->public_key = $response->publicKey;
    }

    /**
     * @return String
     */
    public function getAccountId(): string
    {
        return $this->account_id;
    }

    /**
     * @return String
     */
    public function getEncryptedKey(): string
    {
        return $this->encrypted_key;
    }

    /**
     * @return String
     */
    public function getPublicKey(): string
    {
        return $this->public_key;
    }
}
