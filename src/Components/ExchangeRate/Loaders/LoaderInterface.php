<?php

namespace App\Components\ExchangeRate\Loaders;

interface LoaderInterface
{
    public function load(): array;
}