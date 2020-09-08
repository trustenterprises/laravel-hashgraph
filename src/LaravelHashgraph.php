<?php

namespace Trustenterprises\LaravelHashgraph;

class LaravelHashgraph
{
    private int $result;

    public function __construct()
    {
        $this->result = 0;
    }

    public function add(int $value)
    {
        $this->result += $value;

        return $this;
    }

    public function subtract(int $value)
    {
        $this->result -= $value;

        return $this;
    }

    public function clear()
    {
        $this->result = 0;

        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }
}
