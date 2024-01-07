<?php

namespace Trustenterprises\LaravelHashgraph\Models;

class TopicInfo
{
    const EMPTY_TOPIC_ID = "-1";

    private String $memo;

    private String $topic_id;

    /**
     * TopicInfo constructor.
     * @param String $memo
     * @param String $topic_id
     */
    public function __construct(string $memo, string $topic_id)
    {
        $this->memo = $memo;
        $this->topic_id = $topic_id;
    }

    public static function emptyTopic(): self
    {
        return new self('', self::EMPTY_TOPIC_ID);
    }

    /**
     * @return String
     */
    public function getMemo(): string
    {
        return $this->memo;
    }

    /**
     * @return String
     */
    public function getTopicId(): string
    {
        return $this->topic_id;
    }
}
