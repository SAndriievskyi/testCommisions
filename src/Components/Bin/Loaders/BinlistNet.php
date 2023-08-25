<?php

namespace App\Components\Bin\Loaders;

use App\Components\Bin\DataObject;
use App\Components\CurlGet;
use Exception;

readonly class BinlistNet implements LoaderInterface
{
    public function __construct(
        private string $url,
    ) {}

    public function load(int $bin): ?DataObject
    {
        $url = $this->url . $bin;
        $curl = new CurlGet($url);
        $response = $curl();
        if (!$response) {
            throw new Exception();
        }
        $response = json_decode($response);

        return new DataObject($response->country->alpha2);
    }
}