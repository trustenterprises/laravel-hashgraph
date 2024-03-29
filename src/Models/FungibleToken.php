<?php


namespace Trustenterprises\LaravelHashgraph\Models;

class FungibleToken
{
    private String $symbol;

    private String $name;

    private String $supply;

    private String $memo;

    private ?int $decimals = null;

    /**
     * FungibleToken constructor.
     * @param String $symbol
     * @param String $name
     * @param String $supply
     * @param String $memo
     */
    public function __construct(string $symbol, string $name, string $supply, string $memo)
    {
        $this->symbol = $symbol;
        $this->name = $name;
        $this->supply = $supply;
        $this->memo = $memo;
    }

    public function forTokenRequest(): array
    {
        $payload = [
            'memo' => $this->getMemo(),
            'symbol' => $this->getSymbol(),
            'name' => $this->getName(),
            'supply' => $this->getSupply(),
        ];

        if ($this->getDecimals()) {
            $payload['decimals'] = $this->getDecimals();
        }

        return $payload;
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
     * @return String
     */
    public function getMemo(): string
    {
        return $this->memo;
    }

    /**
     * @param String $memo
     */
    public function setMemo(string $memo): void
    {
        $this->memo = $memo;
    }

    /**
     * @return int|null
     */
    public function getDecimals(): ?int
    {
        return $this->decimals;
    }

    /**
     * @param int|null $decimals
     */
    public function setDecimals(?int $decimals): void
    {
        $this->decimals = $decimals;
    }
}
