<?php

namespace Trustenterprises\LaravelHashgraph\Tests;

use Trustenterprises\LaravelHashgraph\LaravelHashgraph;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\BurnInscription;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\DeployInscription;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\MintInscription;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\TransferInscription;
use Trustenterprises\LaravelHashgraph\Models\NFT\ClaimNft;
use Trustenterprises\LaravelHashgraph\Models\NFT\MintToken;
use Trustenterprises\LaravelHashgraph\Models\NFT\NftMetadata;
use Trustenterprises\LaravelHashgraph\Models\NFT\NonFungibleToken;
use Trustenterprises\LaravelHashgraph\Models\NFT\TransferNft;

class HashgraphInscriptionTest extends TestCase
{
    private static $TOPIC_ID = "7459744";

    /**
     * Attempt to deploy an inscription
     *
     * @test
     */
    public function deploy_an_inscription()
    {
        $deploy = new DeployInscription('TICK1', 'Ticker', 300);

        $deploy->setPrivateTopic(self::$TOPIC_ID);

        $result = LaravelHashgraph::deployInscription($deploy);

        $inscription = $result->getInscription();

        $this->assertTrue($result->hasSucceeded());

        $this->assertEquals(300, $inscription->max);
        $this->assertEquals('TICK1', $inscription->tick);
        $this->assertEquals('Ticker', $inscription->name);
        $this->assertEquals('deploy', $inscription->op);
        $this->assertEquals('hcs-20', $inscription->p);
    }

    /**
     * Attempt to mint an inscription
     *
     * @test
     */
    public function mint_an_inscription()
    {
        $mint = new MintInscription('TICK1', '0.0.1', 1);

        $mint->setPrivateTopic(self::$TOPIC_ID);

        $result = LaravelHashgraph::mintInscription($mint);

        $inscription = $result->getInscription();

        $this->assertTrue($result->hasSucceeded());

        $this->assertEquals('TICK1', $inscription->tick);
        $this->assertEquals('0.0.1', $inscription->to);
        $this->assertEquals(1, $inscription->amt);
        $this->assertEquals('mint', $inscription->op);
        $this->assertEquals('hcs-20', $inscription->p);
    }


    /**
     * Attempt to burn an inscription
     *
     * @test
     */
    public function burn_an_inscription()
    {
        $burn = new BurnInscription('TICK1', '0.0.1', 1);

        $burn->setPrivateTopic(self::$TOPIC_ID);

        $result = LaravelHashgraph::burnInscription($burn);

        $inscription = $result->getInscription();

        $this->assertTrue($result->hasSucceeded());

        $this->assertEquals('TICK1', $inscription->tick);
        $this->assertEquals('0.0.1', $inscription->from);
        $this->assertEquals(1, $inscription->amt);
        $this->assertEquals('burn', $inscription->op);
        $this->assertEquals('hcs-20', $inscription->p);
    }

    /**
     * Attempt to transfer an inscription
     *
     * @test
     */
    public function transfer_a_inscription()
    {
        $transfer = new TransferInscription('TICK1', '0.0.1', '0.0.2', 1);

        $transfer->setPrivateTopic(self::$TOPIC_ID);

        $result = LaravelHashgraph::transferInscription($transfer);

        $inscription = $result->getInscription();

        $this->assertTrue($result->hasSucceeded());

        $this->assertEquals('TICK1', $inscription->tick);
        $this->assertEquals('0.0.1', $inscription->from);
        $this->assertEquals('0.0.2', $inscription->to);
        $this->assertEquals(1, $inscription->amt);
        $this->assertEquals('transfer', $inscription->op);
        $this->assertEquals('hcs-20', $inscription->p);
    }
}
