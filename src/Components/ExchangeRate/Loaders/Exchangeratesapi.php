<?php

namespace App\Components\ExchangeRate\Loaders;

use App\Components\CurlGet;
use Exception;

readonly class Exchangeratesapi implements LoaderInterface
{
    public function __construct(
        private string $url,
        private string $apiKey,
    ) {}

    public function load(): array
    {
        $url = $this->url . '?access_key=' . $this->apiKey;
        $curl = new CurlGet($url);
        $response = $curl();
        if (!$response) {
            throw new Exception();
        }
        $response = json_decode($response);

        return (array)$response->rates;
    }
}