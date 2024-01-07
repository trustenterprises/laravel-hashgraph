<?php

namespace Trustenterprises\LaravelHashgraph\Models\Inscriptions;

use GuzzleHttp\RequestOptions;

class BurnInscription
{
    private String $from;

    private int $amount;

    private String $ticker;

    private ?String $memo = null;

    private ?String $topic_id = null;

    /**
     * FungibleToken constructor.
     * @param string $ticker
     * @param string $from
     * @param int $amount
     */
    public function __construct(string $ticker, string $from, int $amount)
    {
        $this->ticker = $ticker;
        $this->from = $from;
        $this->amount = $amount;
    }

    public function forRequest(): array
    {
        $payload = [
            'from' => $this->getFrom(),
            'amount' => $this->getAmount(),
        ];

        if ($this->getMemo()) {
            $payload['memo'] = $this->getMemo();
        }

        if ($this->getPrivateTopicId()) {
            $payload['topic_id'] = $this->getPrivateTopicId();
        }

        return [ RequestOptions::JSON => $payload ];
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
    public function setTicker($ticker): void
    {
        $this->ticker = $ticker;
    }

    /**
     * @return null|string
     */
    public function getMemo(): string|null
    {
        return $this->memo;
    }

    /**
     * @param String $memo
     */
    public function setMemo($memo): void
    {
        $this->memo = $memo;
    }

    /**
     * @return null|string
     */
    public function getPrivateTopicId(): string|null
    {
        return $this->topic_id;
    }

    /**
     * @param String $topic_id
     */
    public function setPrivateTopic($topic_id): void
    {
        $this->topic_id = $topic_id;
    }

    /**
     * @return string|string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string|string $from
     */
    public function setFrom($from): void
    {
        $this->from = $from;
    }

    /**
     * @return int|int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int|int $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }
}

