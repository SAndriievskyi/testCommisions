<?php

namespace App\Components\Bin;

use App\Components\Bin\Loaders\LoaderInterface;

class Factory
{
    public function __construct(
        private readonly LoaderInterface $loader,
    ) {}

    /** cache data */
    public function retrieveBin(int $bin): ?DataObject
    {
        return $this->loader->load($bin);
    }
}