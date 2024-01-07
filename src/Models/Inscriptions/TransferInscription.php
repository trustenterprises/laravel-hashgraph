<?php

namespace Trustenterprises\LaravelHashgraph\Models\Inscriptions;

use GuzzleHttp\RequestOptions;

class TransferInscription
{
    private String $from;

    private String $to;

    private int $amount;

    private String $ticker;

    private ?String $memo = null;

    private ?String $topic_id = null;

    /**
     * FungibleToken constructor.
     * @param string $ticker
     * @param string $from
     * @param string $to
     * @param int $amount
     */
    public function __construct(string $ticker, string $from, string $to, int $amount)
    {
        $this->ticker = $ticker;
        $this->from = $from;
        $this->to = $to;
        $this->amount = $amount;
    }

    public function forRequest(): array
    {
        $payload = [
            'from' => $this->getFrom(),
            'to' => $this->getTo(),
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
    public function setTicker($ticker)
    {
        $this->ticker = $ticker;
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
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string|string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string|string $to
     */
    public function setTo($to)
    {
        $this->to = $to;
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
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
}
