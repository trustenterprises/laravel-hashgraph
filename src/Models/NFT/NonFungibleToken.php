<?php


namespace Trustenterprises\LaravelHashgraph\Models\NFT;

class NonFungibleToken
{
    private String $symbol;

    private String $name;

    private Int $supply;

    // Optional values below
    private bool $enable_unsafe_keys = false;

    private bool $has_royalties = true;

    private ?String $royalty_account_id = null;

    private float $royalty_fee = 0.05;

    private float $fallback_fee = 0;

    /**
     * FungibleToken constructor.
     * @param String $symbol
     * @param String $name
     * @param Int $supply
     */
    public function __construct(string $symbol, string $name, Int $supply)
    {
        $this->symbol = $symbol;
        $this->name = $name;
        $this->supply = $supply;
    }

    public function forNftRequest(): array
    {
        $token_payload = [
            'symbol' => $this->getSymbol(),
            'collection_name' => $this->getName(),
            'supply' => $this->getSupply(),
        ];

        if ($this->enableUnsafeKeys()) {
            $token_payload['enable_unsafe_keys'] = true;
        }

        if ($this->hasRoyalties()) {

            $token_payload['royalty_fee'] = $this->getRoyaltyFee();

            if ($this->getRoyaltyAccountId()) {
                $token_payload['royalty_account_id'] = $this->getRoyaltyAccountId();
            }

            if ($this->getFallbackFee() > 0) {
                $token_payload['fallback_fee'] = $this->getFallbackFee();
            }
        }

        return $token_payload;
    }

    /**
     * @return String
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param String $symbol
     */
    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }

    /**
     * @return String
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return String
     */
    public function getSupply(): string
    {
        return $this->supply;
    }

    /**
     * @param String $supply
     */
    public function setSupply(string $supply): void
    {
        $this->supply = $supply;
    }

    /**
     * @return bool
     */
    public function enableUnsafeKeys(): bool
    {
        return $this->enable_unsafe_keys;
    }

    /**
     * @return NonFungibleToken
     */
    public function WARNING_enableUnsafeKeys(): self
    {
        $this->enable_unsafe_keys = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasRoyalties(): bool
    {
        return $this->has_royalties;
    }

    public function enableRoyalties(bool $has_royalties = true): self
    {
        $this->has_royalties = $has_royalties;

        return $this;
    }

    /**
     * @return float
     */
    public function getRoyaltyFee(): float
    {
        return $this->royalty_fee;
    }

    /**
     * @param float $royalty_fee
     */
    public function setRoyaltyFee(float $royalty_fee = 0.05): self
    {
        $this->royalty_fee = $royalty_fee;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getFallbackFee()
    {
        return $this->fallback_fee;
    }

    /**
     * @param float|int $fallback_fee
     */
    public function setFallbackFee($fallback_fee = 0): self
    {
        $this->fallback_fee = $fallback_fee;

        return $this;
    }

    /**
     * @return String
     */
    public function getRoyaltyAccountId(): ?string
    {
        return $this->royalty_account_id;
    }

    public function setRoyaltyAccountId(string $royalty_account_id): self
    {
        $this->royalty_account_id = $royalty_account_id;

        return $this;
    }
}
