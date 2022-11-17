<?php

namespace Trustenterprises\LaravelHashgraph\Tests;

use Trustenterprises\LaravelHashgraph\LaravelHashgraph;
use Trustenterprises\LaravelHashgraph\Models\NFT\BatchTransferNft;
use Trustenterprises\LaravelHashgraph\Models\NFT\ClaimNft;
use Trustenterprises\LaravelHashgraph\Models\NFT\MintToken;
use Trustenterprises\LaravelHashgraph\Models\NFT\NftMetadata;
use Trustenterprises\LaravelHashgraph\Models\NFT\NonFungibleToken;
use Trustenterprises\LaravelHashgraph\Models\NFT\TransferNft;

class HashgraphBatchNftTest extends TestCase
{
    /**
     * Attempt to send a pre-minted NFT
     *
     * @test-ignore
     */
    public function attempt_batch_transfer()
    {

        $account = LaravelHashgraph::createAccount();

        // Mirrornode ain't real time...
        $nft_id = '0.0.48905313';
        $batch_amount = 19;

        $batch = new BatchTransferNft($nft_id, $account->getAccountId(), $batch_amount);

        $result = LaravelHashgraph::batchTransferNonFungibleToken($batch);

        $this->assertEquals($batch_amount, $result->getActualSent());
        $this->assertEquals($batch_amount, $result->getExpected());
    }

    /**
     * Attempt to send to many NFTs
     *
     * @test
     */
    public function attempt_failed_too_many_batch_transfer()
    {
        $account = LaravelHashgraph::createAccount();

        // Mirrornode ain't real time...
        $nft_id = '0.0.48905313';
        $batch_amount = 1009;

        $batch = new BatchTransferNft($nft_id, $account->getAccountId(), $batch_amount);

        $result = LaravelHashgraph::batchTransferNonFungibleToken($batch);

        $this->assertEquals('The treasury does not hold the amount of NFTs of id ' . $nft_id . ' to do the required batch transfer', $result->getErrors()[0]);
    }


}
