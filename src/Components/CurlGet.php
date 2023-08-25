<?php

namespace App\Components;

use RuntimeException;

readonly class CurlGet
{
    public function __construct(
        private string $url,
        private array $options = [],
    ) {}

    public function __invoke(): string
    {
        $ch = curl_init();
        foreach ($this->options as $key => $val) {
            curl_setopt($ch, $key, $val);
        }
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $errno = curl_errno($ch);

        if (is_resource($ch)) {
            curl_close($ch);
        }
        if (0 !== $errno) {
            throw new RuntimeException($error, $errno);
        }

        return $response;
    }
}