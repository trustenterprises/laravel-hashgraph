# Laravel Hashgraph API, create NFTs, permissioned marketplaces, or create a foundational L2 with base consensus.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/trustenterprises/hashgraph.svg?style=flat-square)](https://packagist.org/packages//laravel-hashgraph)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/mattsmithies/laravel-hashgraph/Tests)](https://github.com//laravel-hashgraph/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/trustenterprises/hashgraph.svg?style=flat-square)](https://packagist.org/packages//laravel-hashgraph)

Trust backed by Hedera Hashgraph using your own serverless REST client by Trust Enterprises.

The project manages any trust events and consensus responses, it is a full solution for managing [webhook functionality](https://docs.trust.enterprises/rest-api/webhooks) after a client has received consensus.

## Support us

We're open to ongoing financial support to help with the continuation of the development of these libraries and projects and help for integration for your current SaaS projects, if you are interested in sponsoring/working with us [please get in contact](https://trust.enterprises/#contact). 

## Installation

You can install the package via composer:

```bash
composer require trustenterprises/hashgraph
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Trustenterprises\LaravelHashgraph\LaravelHashgraphServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Trustenterprises\LaravelHashgraph\LaravelHashgraphServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
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

    /***
     * The webhook URL that can be configured to receive message events from your Serverless REST API.
     **/
    'webhook_route' => env('HASHGRAPH_WEBHOOK_ROUTE', '/hashgraph'),
];

```

## Deployment with the hedera serverless client

This is a Laravel library and introduces a migration, listenable events and a new webhook route.

Set the **WEBHOOK_URL** in your serverless client to *domain.com/hashgraph* where domain is your URL. Read more about our [usage of webhooks](https://docs.trust.enterprises/rest-api/webhooks). 

For local development you can use ngrok to expose your local server.

## Usage


``` php

// Your Imports
use Trustenterprises\LaravelHashgraph\LaravelHashgraph;
use Trustenterprises\LaravelHashgraph\Models\ConsensusMessage;

// Code
$message = new ConsensusMessage('This is an event you wish to store');
$message->setReference('MattSmithies'); // optional

LaravelHashgraph::withTopic('Trust Enterprises')->sendMessage($message);
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email info@mattsmithies.co.uk instead of using the issue tracker.

## Credits

- [Matt Smithies](https://github.com/MattSmithies)
- [All Contributors](../../contributors)

## License

The Apache 2.0 License. Please see [License File](LICENSE.md) for more information.
