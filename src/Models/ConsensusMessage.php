<?php


namespace Trustenterprises\LaravelHashgraph\Models;

class ConsensusMessage
{
    private String $topic_id;

    private String $message;

    private ?string $reference = null;

    private bool $allow_synchronous_consensus = false;

    /**
     * ConsensusMessage constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function forMessageRequest(): array
    {
        $message_payload = [
            'message' => $this->getMessage(),
            'topic_id' => $this->getTopicId(),
        ];

        $allow_synchronous = $this->isAllowSynchronousConsensus();
        $reference = $this->getReference();

        if ($allow_synchronous) {
            $message_payload['allow_synchronous_consensus'] = true;
        }

        if ($reference) {
            $message_payload['reference'] = $reference;
        }

        return $message_payload;
    }

    /**
     * @return String
     */
    public function getTopicId(): string
    {
        return $this->topic_id;
    }

    /**
     * @param String $topic_id
     */
    public function setTopicId(string $topic_id): void
    {
        $this->topic_id = $topic_id;
    }

    /**
     * @return String
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param String $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string|null $reference
     */
    public function setReference(?string $reference): void
    {
        $this->reference = $reference;
    }

    /**
     * @return bool
     */
    public function isAllowSynchronousConsensus(): bool
    {
        return $this->allow_synchronous_consensus;
    }

    /**
     * @param bool $allow_synchronous_consensus
     */
    public function setAllowSynchronousConsensus(bool $allow_synchronous_consensus): void
    {
        $this->allow_synchronous_consensus = $allow_synchronous_consensus;
    }
}
