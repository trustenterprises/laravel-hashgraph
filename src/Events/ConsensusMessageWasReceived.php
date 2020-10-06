<?php

namespace Trustenterprises\LaravelHashgraph\Events;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Trustenterprises\LaravelHashgraph\Models\HashgraphConsensusMessage;

class ConsensusMessageWasReceived
{
    use Dispatchable, SerializesModels;

    public HashgraphConsensusMessage $consensus_message;

    public function __construct(HashgraphConsensusMessage $consensus_message)
    {
        $this->consensus_message = $consensus_message;
    }
}
