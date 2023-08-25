<?php

namespace App\Components\Bin;

readonly final class DataObject
{
    public function __construct(
        private string $countryAlpha2,
    ) {}

    public function getCountryAlpha2(): string
    {
        return $this->countryAlpha2;
    }
}