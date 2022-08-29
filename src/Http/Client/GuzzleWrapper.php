<?php

namespace Trustenterprises\LaravelHashgraph\Http\Client;

use GuzzleHttp\Client;

class GuzzleWrapper
{
    public static function getAuthenticatedGuzzleInstance(): Client
    {
        return new Client([
            'base_uri' => config('hashgraph.client_url'),
            'headers' => [
                'Accept' => 'application/json',
                'x-api-key' => config('hashgraph.secret_key'),
            ],
            'http_errors' => false
        ]);
    }
}
