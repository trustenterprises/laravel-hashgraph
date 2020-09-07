<?php

namespace Trustenterprises\LaravelHashgraph\Http\Controllers;

use Illuminate\Routing\Controller;

class LaravelHashgraphWebhookController extends Controller
{
    public function __invoke()
    {
        return "ok";
    }
}
