<?php

namespace App\Components\ExchangeRate;

use App\Components\ExchangeRate\Loaders\LoaderInterface;

class Factory
{
    public function __construct(
        private readonly LoaderInterface $loader,
    ) {}

    /** cache data */
    public function retrieveRates(): array
    {
        return $this->loader->load();
    }
}