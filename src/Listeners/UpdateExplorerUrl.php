<?php

namespace Trustenterprises\LaravelHashgraph\Listeners;

use Trustenterprises\LaravelHashgraph\Events\ConsensusMessageWasReceived;

class UpdateExplorerUrl
{
    public function handle(ConsensusMessageWasReceived $event): void
    {
        $event->consensus_message->update([
           'explorer_url' => 'https://trust.enterprises/',
        ]);
    }
}
