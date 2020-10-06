<?php


namespace Trustenterprises\LaravelHashgraph\Events;


use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Trustenterprises\LaravelHashgraph\Models\HashgraphTopic;

class TopicWasCreated
{
    use Dispatchable, SerializesModels;

    public HashgraphTopic $topic;

    public function __construct(HashgraphTopic $topic)
    {
        $this->topic = $topic;
    }
}
