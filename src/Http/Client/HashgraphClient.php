<?php


namespace Trustenterprises\LaravelHashgraph\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Trustenterprises\LaravelHashgraph\Contracts\HashgraphConsensus;
use Trustenterprises\LaravelHashgraph\Contracts\InscriptionMethodInterface;
use Trustenterprises\LaravelHashgraph\Models\AccountCreateResponse;
use Trustenterprises\LaravelHashgraph\Models\AccountHoldingsResponse;
use Trustenterprises\LaravelHashgraph\Models\AccountTokenBalanceResponse;
use Trustenterprises\LaravelHashgraph\Models\BequestToken;
use Trustenterprises\LaravelHashgraph\Models\BequestTokenResponse;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\BurnInscription;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\DeployInscription;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\MintInscription;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\InscriptionResponse;
use Trustenterprises\LaravelHashgraph\Models\Inscriptions\TransferInscription;
use Trustenterprises\LaravelHashgraph\Models\NFT\BatchTransferNft;
use Trustenterprises\LaravelHashgraph\Models\NFT\BatchTransferNftResponse;
use Trustenterprises\LaravelHashgraph\Models\NFT\ClaimNft;
use Trustenterprises\LaravelHashgraph\Models\NFT\ClaimNftResponse;
use Trustenterprises\LaravelHashgraph\Models\NFT\MintToken;
use Trustenterprises\LaravelHashgraph\Models\NFT\MintTokenResponse;
use Trustenterprises\LaravelHashgraph\Models\NFT\NftMetadata;
use Trustenterprises\LaravelHashgraph\Models\NFT\NftMetadataResponse;
use Trustenterprises\LaravelHashgraph\Models\NFT\NonFungibleToken;
use Trustenterprises\LaravelHashgraph\Models\NFT\NonFungibleTokenResponse;
use Trustenterprises\LaravelHashgraph\Models\NFT\TransferNft;
use Trustenterprises\LaravelHashgraph\Models\NFT\TransferNftResponse;
use Trustenterprises\LaravelHashgraph\Models\SendTokenResponse;
use Trustenterprises\LaravelHashgraph\Models\SendToken;
use Trustenterprises\LaravelHashgraph\Models\ConsensusMessage;
use Trustenterprises\LaravelHashgraph\Models\ConsensusMessageResponse;
use Trustenterprises\LaravelHashgraph\Models\FungibleToken;
use Trustenterprises\LaravelHashgraph\Models\FungibleTokenResponse;
use Trustenterprises\LaravelHashgraph\Models\TopicInfo;

/**
 * Class ServerlessHashgraphClient
 * @package Trustenterprises\LaravelHashgraph\Http\Client
 */
class HashgraphClient implements HashgraphConsensus, InscriptionMethodInterface
{
    /**
     * @var Client
     */
    private Client $guzzle;

    /**
     * ServerlessHashgraphClient constructor.
     * @param Client $guzzle
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @return HashgraphConsensus
     */
    public static function createAuthorisedInstance() : HashgraphConsensus
    {
        $guzzle = GuzzleWrapper::getAuthenticatedGuzzleInstance();

        return new self($guzzle);
    }

    /**
     * @return string
     * @throws GuzzleException
     */
    public function getBalance(): string
    {
        $response = $this->guzzle->get('api/account/balance');
        $body = json_decode($response->getBody()->getContents());

        return $body->data->balance;
    }

    /**
     * @param string $id
     * @return TopicInfo
     * @throws GuzzleException
     */
    public function getTopicInfo(string $id): TopicInfo
    {
        $response = $this->guzzle->get('api/consensus/topic/' . $id);
        $data = json_decode($response->getBody()->getContents())->data;

        return new TopicInfo($data->topicMemo, $id);
    }

    /**
     * @param string $memo
     * @return TopicInfo
     * @throws GuzzleException
     */
    public function createTopic(string $memo): TopicInfo
    {
        $response = $this->guzzle->post('api/consensus/topic', [
            'json' => [
                'memo' => $memo,
                'enable_private_submit_key' => true,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents())->data;

        return new TopicInfo($data->memo, $data->topic);
    }

    /**
     * @param string $id
     * @param string $memo
     * @return TopicInfo
     * @throws GuzzleException
     */
    public function updateTopic(string $id, string $memo): TopicInfo
    {
        $response = $this->guzzle->put('api/consensus/topic/' . $id, [
            'json' => [
                'memo' => $memo,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents())->data;

        return new TopicInfo($data->memo, $data->topic_id);
    }

    /**
     * @param ConsensusMessage $message
     * @return ConsensusMessageResponse
     * @throws GuzzleException
     */
    public function sendMessageConsensus(ConsensusMessage $message): ConsensusMessageResponse
    {
        $response = $this->guzzle->post('api/consensus/message', [
            'json' => $message->forMessageRequest(),
        ]);

        $data = json_decode($response->getBody()->getContents())->data;

        return new ConsensusMessageResponse($data);
    }

    /**
     * @param FungibleToken $token
     * @return FungibleTokenResponse
     * @throws GuzzleException
     */
    public function mintFungibleToken(FungibleToken $token): FungibleTokenResponse
    {
        $response = $this->guzzle->post('api/token', [
            'json' => $token->forTokenRequest(),
        ]);

        $data = json_decode($response->getBody()->getContents())->data;

        return new FungibleTokenResponse($data);
    }

    /**
     * @return AccountCreateResponse
     * @throws GuzzleException
     */
    public function createAccount(): AccountCreateResponse
    {
        $response = $this->guzzle->post('api/account/create');

        $data = json_decode($response->getBody()->getContents())->data;

        return new AccountCreateResponse($data);
    }

    /**
     * @param BequestToken $bequestToken
     * @return BequestTokenResponse
     * @throws GuzzleException
     */
    public function bequestToken(BequestToken $bequestToken): BequestTokenResponse
    {
        $response = $this->guzzle->post('api/token/bequest', [
            'json' => $bequestToken->forRequest(),
        ]);

        $data = json_decode($response->getBody()->getContents())->data;

        return new BequestTokenResponse($data);
    }

    /**
     * @param string $account_id
     * @param string $token_id
     * @param int|null $decimals
     * @return AccountTokenBalanceResponse
     * @throws GuzzleException
     */
    public function getTokenBalance(string $account_id, string $token_id, ?int $decimals = null): AccountTokenBalanceResponse
    {
        $uri = 'api/account/' . $account_id . '/' . $token_id;

        // Explicitly append decimal param.
        if ($decimals) {
            $uri .= '?decimals=' . $decimals;
        }

        $response = $this->guzzle->get($uri);

        $data = json_decode($response->getBody()->getContents())->data;

        return new AccountTokenBalanceResponse($data);
    }


    /**
     * @param string $account_id
     * @param string $token_ids
     * @return AccountHoldingsResponse
     * @throws GuzzleException
     */
    public function checkTokenHoldings(string $account_id, string $token_ids): AccountHoldingsResponse
    {
        // Mocking this atm.
        $response = $this->guzzle->get('api/account/' . $account_id . '/holdings/' . $token_ids . ',' .$token_ids);

        $data = json_decode($response->getBody()->getContents())->data;

        return new AccountHoldingsResponse($data);
    }

    /**
     * @param SendToken $sendToken
     * @return SendTokenResponse
     * @throws GuzzleException
     */
    public function sendToken(SendToken $sendToken): SendTokenResponse
    {
        $response = $this->guzzle->post('api/token/send', [
            'json' => $sendToken->forRequest(),
        ]);

        $data = json_decode($response->getBody()->getContents())->data;

        return new SendTokenResponse($data);
    }

    /**
     *
     *  $nft = (new NonFungibleToken('Matt','Matt NFT',123))
            ->enableRoyalties()
            ->setFallbackFee(1)
            ->setRoyaltyFee(0.1)
            ->setRoyaltyAccountId('0.0.1156')
            ->WARNING_enableUnsafeKeys();
     *
     * @param NonFungibleToken $nft
     * @return NonFungibleTokenResponse
     * @throws GuzzleException
     */
    public function createNft(NonFungibleToken $nft): NonFungibleTokenResponse {
        $response = $this->guzzle->post('api/nft', [
            'json' => $nft->forNftRequest()
        ]);

        $data = json_decode($response->getBody()->getContents());

        return new NonFungibleTokenResponse($data);
    }

    /**
     * Mint a token from a collection with a cid
     *
     * @param String $token_id
     * @param MintToken $mint
     * @return MintTokenResponse
     * @throws GuzzleException
     */
    public function mintNft(String $token_id, MintToken $mint): MintTokenResponse {
        $response = $this->guzzle->post('api/nft/' . $token_id . '/mint', [
            'json' => $mint->forRequest()
        ]);

        $data = json_decode($response->getBody()->getContents());

        return new MintTokenResponse($data);
    }

    /**
     * Generate a CID with viable HIP412 compliant metadata using NFT storage
     *
     * @param NftMetadata $metadata
     * @return NftMetadataResponse
     * @throws GuzzleException
     */
    public function createMetadata(NftMetadata $metadata): NftMetadataResponse {
        $response = $this->guzzle->post('api/nft/metadata', $metadata->forRequest());

        $data = json_decode($response->getBody()->getContents());

        return new NftMetadataResponse($data);
    }

    /**
     * Transfer an NFT from a treasury to a user.
     *
     * @param TransferNft $transferNft
     * @return TransferNftResponse
     * @throws GuzzleException
     */
    public function transferNft(TransferNft $transferNft): TransferNftResponse {
        $response = $this->guzzle->post('api/nft/transfer', $transferNft->forRequest());

        $data = json_decode($response->getBody()->getContents());

        return new TransferNftResponse($data);
    }

    /**
     * Transfer a batch of NFTs from a treasury to a user.
     *
     * @param BatchTransferNft $transferNft
     * @return BatchTransferNftResponse
     * @throws GuzzleException
     */
    public function batchTransferNft(BatchTransferNft $transferNft): BatchTransferNftResponse {
        $response = $this->guzzle->post('api/nft/batch', $transferNft->forRequest());

        $data = json_decode($response->getBody()->getContents());

        return new BatchTransferNftResponse($data);
    }

    /**
     * @param ClaimNft $claimNft
     * @return ClaimNftResponse
     * @throws GuzzleException
     */
    public function claimNft(ClaimNft $claimNft): ClaimNftResponse {
        $response = $this->guzzle->post('api/nft/claim', $claimNft->forRequest());

        $data = json_decode($response->getBody()->getContents());

        return new ClaimNftResponse($data);
    }

    /**
     * HCS20 Inscription Methods
     *
     * @param DeployInscription $request
     */
    public function deployInscription(DeployInscription $request): InscriptionResponse
    {
        $response = $this->guzzle->post('api/inscription/deploy', $request->forRequest());

        $data = json_decode($response->getBody()->getContents());

        return new InscriptionResponse($data);
    }

    public function mintInscription(MintInscription $request): InscriptionResponse
    {
        $response = $this->guzzle->post("api/inscription/{$request->getTicker()}/mint", $request->forRequest());

        $data = json_decode($response->getBody()->getContents());

        return new InscriptionResponse($data);
    }

    public function burnInscription(BurnInscription $request): InscriptionResponse
    {
        $response = $this->guzzle->post("api/inscription/{$request->getTicker()}/burn", $request->forRequest());

        $data = json_decode($response->getBody()->getContents());

        return new InscriptionResponse($data);
    }

    public function transferInscription(TransferInscription $request): InscriptionResponse
    {
        $response = $this->guzzle->post("api/inscription/{$request->getTicker()}/transfer", $request->forRequest());

        $data = json_decode($response->getBody()->getContents());

        return new InscriptionResponse($data);
    }
}
