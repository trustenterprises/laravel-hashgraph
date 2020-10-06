<?php

return [

    /***
     * The URL of your serverless hashgraph client that has been configured through the serverless
     * provider, likely to be vercel.
     **/
    'client_url' => env('HASHGRAPH_NODE_URL'),

    /***
     * The generated secret key that you have set for your serverless hashgraph client.
     **/
    'secret_key' => env('HASHGRAPH_SECRET_KEY'),
];
