<?php

namespace App\Components\Commission\DataLoaders;

use App\Components\Commission\DataTransformer;
use Psr\Log\LoggerInterface;

class Factory
{
    public function __construct(
        private readonly DataTransformer $dataTransformer,
        private readonly ?LoggerInterface $logger,
    ) {}

    public function createFileDataLoader(string $filePath): FileDataLoader
    {
        return new FileDataLoader($filePath, $this->dataTransformer, $this->logger);
    }
}