<?php

namespace Trustenterprises\LaravelHashgraph\Utilities;

class ConvertToProofId
{
    /**
     * Convert a default Hedera Id to be suitable for proof ids
     *
     * @param $transaction_id
     * @return string
     */
    public static function create(string $transaction_id): string
    {
        $split_tx = explode('@', $transaction_id);
        $ts = explode('.', $split_tx[1]);
        $tx_items = array($split_tx[0], $ts[0], $ts[1]);

        return implode('-', $tx_items);
    }
}
