<?php

namespace Trustenterprises\LaravelHashgraph\Utilities;

class Hmac
{
    /**
     * Generate a HMAC from request content to validate that a payload matches with a x-signature
     *
     * @link https://github.com/symfony/http-foundation/blob/master/Request.php#L1535 Documentation of Foo.
     * @param $content
     * @return string
     */
    public static function generate($content): string
    {
        return hash_hmac('sha256', $content, config('hashgraph.secret_key'));
    }
}
