<?php

namespace App\Components\Commission;

use App\Enums\Currency;

readonly final class DataObject
{
   public function __construct(
       private int $bin,
       private float $amount,
       private Currency $currency,
   ) {}

    public function getBin(): int
    {
        return $this->bin;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}