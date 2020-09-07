<?php

namespace Trustenterprises\LaravelHashgraph;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Trustenterprises\LaravelHashgraph\LaravelHashgraph
 */
class LaravelHashgraphFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-hashgraph';
    }
}
