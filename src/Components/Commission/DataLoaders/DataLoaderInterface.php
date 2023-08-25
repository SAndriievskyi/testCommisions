<?php

namespace App\Components\Commission\DataLoaders;

use App\Components\Commission\DataObject;

interface DataLoaderInterface
{
    /**
     * @return DataObject[]
     */
    public function load(): array;
}