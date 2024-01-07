<?php


namespace Trustenterprises\LaravelHashgraph\Models\Inscriptions;

/**
 * {
        "data": {
            "topic_id": "7459744",
            "transaction_id": "0.0.135908@1704558798.734620091",
            "explorer_url": "https://ledger-testnet.hashlog.io/tx/0.0.135908@1704558798.734620091",
            "inscription": {
                "p": "hcs-20",
                "op": "mint",
                "tick": "TICK",
                "amt": "1",
                "to": "0.0.1156"
            }
        }
 * }
 */

class InscriptionResponse
{
    private String $topic_id;

    private String $transaction_id;

    private String $explorer_url;

    private object $inscription;

    private array $errors = [];

    private bool $transfer_success = false;

    /**
     * ConsensusMessageResponse constructor, using the http response contents body object
     * @param object $response
     */
    public function __construct(object $response)
    {

        if (property_exists($response, 'errors')) {
            $this->errors = $response->errors;
        } else {
            $this->transfer_success = true;
            $this->topic_id = $response->data->topic_id;
            $this->transaction_id = $response->data->transaction_id;
            $this->explorer_url = $response->data->explorer_url;
            $this->inscription = (object) $response->data->inscription;
        }
    }

    /**
     * @return bool
     */
    public function hasSucceeded(): bool
    {
        return $this->transfer_success;
    }

    /**
     * @return String
     */
    public function getTopicId(): String
    {
        return $this->topic_id;
    }

    /**
     * @return String
     */
    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }

    /**
     * @return String
     */
    public function getExplorerUrl(): string
    {
        return $this->explorer_url;
    }

    /**
     * @return object
     */
    public function getInscription(): object
    {
        return $this->inscription;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
