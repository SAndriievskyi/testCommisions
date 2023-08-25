<?php

namespace App\Components\Bin\Loaders;

use App\Components\Bin\DataObject;

interface LoaderInterface
{
    public function load(int $bin): ?DataObject;
}