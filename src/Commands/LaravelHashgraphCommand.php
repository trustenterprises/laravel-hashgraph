<?php

namespace Trustenterprises\LaravelHashgraph\Commands;

use Illuminate\Console\Command;
use Trustenterprises\LaravelHashgraph\LaravelHashgraph;

class LaravelHashgraphCommand extends Command
{
    public $signature = 'hashgraph:test';

    public $description = 'My command';

    public function handle()
    {
//        LaravelHashgraphFacade::
//        error_log($client->getBalance());
//        $this->comment('All done');

        error_log(LaravelHashgraph::balance());
    }
}
