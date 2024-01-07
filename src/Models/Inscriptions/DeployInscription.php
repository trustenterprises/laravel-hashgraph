<?php

namespace Trustenterprises\LaravelHashgraph\Models\Inscriptions;

use GuzzleHttp\RequestOptions;

class DeployInscription
{
    private String $name;

    private String $ticker;

    private int $max;

    private ?int $limit = null;

    private ?String $metadata = null;

    private ?String $memo = null;

    private ?String $topic_id = null;

    /**
     * FungibleToken constructor.
     * @param string $ticker
     * @param string $name
     * @param int $max
     */
    public function __construct(string $ticker, string $name, int $max)
    {
        $this->ticker = $ticker;
        $this->name = $name;
        $this->max = $max;
    }

    public function forRequest(): array
    {
        $payload = [
            'ticker' => $this->getTicker(),
            'name' => $this->getName(),
            'max' => $this->getMax(),
        ];

        if ($this->getLimit()) {
            $payload['limit'] = $this->getLimit();
        }

        if ($this->getMemo()) {
            $payload['memo'] = $this->getMemo();
        }

        if ($this->getMetadata()) {
            $payload['metadata'] = $this->getMetadata();
        }

        if ($this->getPrivateTopicId()) {
            $payload['topic_id'] = $this->getPrivateTopicId();
        }

        return [ RequestOptions::JSON => $payload ];
    }

    /**
     * @return String|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param String|string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return String|string
     */
    public function getTicker()
    {
        return $this->ticker;
    }

    /**
     * @param String|string $ticker
     */
    public function setTicker($ticker)
    {
        $this->ticker = $ticker;
    }

    /**
     * @return int|Integer
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param int|Integer $max
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return String
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param String $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return String
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * @param String $memo
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;
    }

    /**
     * @return String
     */
    public function getPrivateTopicId()
    {
        return $this->topic_id;
    }

    /**
     * @param String $topic_id
     */
    public function setPrivateTopic($topic_id)
    {
        $this->topic_id = $topic_id;
    }
}

