<?php

namespace Trustenterprises\LaravelHashgraph\Http\Client\Models;

class TopicInfo
{
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
