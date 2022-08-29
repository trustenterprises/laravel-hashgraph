<?php

namespace Trustenterprises\LaravelHashgraph\Tests;

use Trustenterprises\LaravelHashgraph\LaravelHashgraph;
use Trustenterprises\LaravelHashgraph\Models\NFT\ClaimNft;
use Trustenterprises\LaravelHashgraph\Models\NFT\MintToken;
use Trustenterprises\LaravelHashgraph\Models\NFT\NftMetadata;
use Trustenterprises\LaravelHashgraph\Models\NFT\NonFungibleToken;
use Trustenterprises\LaravelHashgraph\Models\NFT\TransferNft;

class HashgraphNftTest extends TestCase
{
    /**
     * Attempt to create an NFT
     *
     * @test
     */
    public function create_a_basic_nft()
    {
        $nft = new NonFungibleToken("NFT", "tNFT", 10);

        $minted = LaravelHashgraph::createNonFungibleToken($nft);

        $this->assertTrue(!!$minted->isSuccessful());
        $this->assertEquals('NFT', $minted->getSymbol());
        $this->assertEquals('tNFT', $minted->getCollectionName());
        $this->assertEquals(10, $minted->getMaxSupply());
    }


    public function create_a_sad_nft()
    {
        $nft = new NonFungibleToken("", "", 0);

        $response = LaravelHashgraph::createNonFungibleToken($nft);

        $errors = $response->getErrors();

        $this->assertEquals('"collection_name" is not allowed to be empty', $errors[0]);
        $this->assertEquals('"symbol" is not allowed to be empty', $errors[1]);
        $this->assertEquals('"supply" must be a positive number', $errors[2]);
    }

    /**
     * Generate metadata from bad input
     *
     * @test
     */
    public function generate_bad_metadata()
    {
        $metadata = new NftMetadata([
            'name_bad' => 'hello'
        ]);

        $response = LaravelHashgraph::generateMetadataForNft($metadata);

        $errors = $response->getErrors();

        $this->assertEquals('"name" is required', $errors[0]);
        $this->assertEquals('"name_bad" is not allowed', $errors[1]);

        $this->assertEquals('Hedera HIP412 "Strict" validation for generating valid network metadata', $response->getErrorMeta()->description);
        $this->assertEquals('https://hips.hedera.com/hip/hip-412', $response->getErrorMeta()->url);
    }

    /**
     * Generate metadata from bad input
     *
     * @test
     */
    public function generate_basic_metadata()
    {
        $metadata = new NftMetadata([
            'name' => 'hello'
        ]);

        $response = LaravelHashgraph::generateMetadataForNft($metadata);

        $this->assertTrue(!!$response->isSuccessful());
        $this->assertTrue(!!$response->getCid());
    }

    /**
     * Mint token from bad input
     *
     * @test
     */
    public function generate_bad_mint_no_cid()
    {
        $mint = new MintToken('123', '', 1);

        $response = LaravelHashgraph::mintNonFungibleToken($mint);

        $errors = $response->getErrors();

        $this->assertEquals('"cid" is not allowed to be empty', $errors[0]);

    }

    /**
     * Mint token from bad input - token
     *
     * @test
     */
    public function generate_bad_mint_no_token()
    {
        $mint = new MintToken('0.0.4793895911111111', 'bafkreihutncu4xpgd2q2ykfyr3xcs3vdhlih76xp6zixx4rru6ufmpxnye', 1);

        $response = LaravelHashgraph::mintNonFungibleToken($mint);

        $errors = $response->getErrors();

        $this->assertEquals('Something went wrong, likely that the token id probably incorrect', $errors[0]);

    }

    /**
     * Mint token
     *
     * @test
     */
    public function generate_mint_token()
    {
        $nft = new NonFungibleToken("NFT", "tNFT", 10);
        $minted = LaravelHashgraph::createNonFungibleToken($nft);

        $mint = new MintToken($minted->getTokenId(), 'bafkreihutncu4xpgd2q2ykfyr3xcs3vdhlih76xp6zixx4rru6ufmpxnye', 1);

        $response = LaravelHashgraph::mintNonFungibleToken($mint);

        $this->assertTrue(!!$response->isSuccessful());
        $this->assertTrue(!!$response->getTokenId());
        $this->assertTrue(!!$response->getAmount());
        $this->assertTrue(!!$response->getSerialNumbers());
    }



    /**
     * Transfer Token
     *
     * @test
     */
    public function transfer_nft_to_account()
    {
        $nft = new NonFungibleToken("NFT", "tNFT", 10);
        $minted = LaravelHashgraph::createNonFungibleToken($nft);

        $mint = new MintToken($minted->getTokenId(), 'bafkreihutncu4xpgd2q2ykfyr3xcs3vdhlih76xp6zixx4rru6ufmpxnye', 1);

        $response = LaravelHashgraph::mintNonFungibleToken($mint);

        $token_id = $response->getTokenId();
        $serial = $response->getSerialNumbers()[0];

        $account = LaravelHashgraph::createAccount();

        $transfer = new TransferNft($token_id, $account->getAccountId(), $serial);

        sleep(2);

        $response = LaravelHashgraph::transferNonFungibleToken($transfer);

        $this->assertTrue(!!$response->getSerialNumber());
        $this->assertTrue(!!$response->getTransactionId());
        $this->assertTrue(!!$response->getReceiverId());
    }

    /**
     * Transfer Token
     *
     * @test
     */
    public function claim_nft_to_account()
    {
        $nft_pass = new NonFungibleToken("NFT_PASS", "NFT_PASS", 1);
        $nft = new NonFungibleToken("NFT_1", "NFT_1", 1);

        $minted_pass = LaravelHashgraph::createNonFungibleToken($nft_pass);
        $minted_1 = LaravelHashgraph::createNonFungibleToken($nft);

        $mint_pass = new MintToken($minted_pass->getTokenId(), 'bafkreihutncu4xpgd2q2ykfyr3xcs3vdhlih76xp6zixx4rru6ufmpxnye', 1);
        $mint_1 = new MintToken($minted_1->getTokenId(), 'bafkreihutncu4xpgd2q2ykfyr3xcs3vdhlih76xp6zixx4rru6ufmpxnye', 1);

        LaravelHashgraph::mintNonFungibleToken($mint_pass);
        LaravelHashgraph::mintNonFungibleToken($mint_1);

        $account = LaravelHashgraph::createAccount();

        sleep(2);

        $claim = LaravelHashgraph::claimNonFungibleToken(new ClaimNft(
            $mint_1->getTokenId(),
            $account->getAccountId(),
            $mint_pass->getTokenId(),
            1,
        ));

        $errors = $claim->getErrors();

        $this->assertEquals('Unfortunately this account does not own the NFT pass required', $errors[0]);

        LaravelHashgraph::transferNonFungibleToken(new TransferNft(
            $mint_pass->getTokenId(),
            $account->getAccountId(),
            1
        ));

        sleep(5);

        $claim = LaravelHashgraph::claimNonFungibleToken(new ClaimNft(
            $mint_1->getTokenId(),
            $account->getAccountId(),
            $mint_pass->getTokenId(),
            1,
        ));

        $this->assertTrue(!!$claim->isSuccessful());
        $this->assertTrue(!!$claim->getSerialNumber());
        $this->assertTrue(!!$claim->getTransactionId());
        $this->assertTrue(!!$claim->getReceiverId());
    }
}
