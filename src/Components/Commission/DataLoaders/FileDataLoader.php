<?php

namespace App\Components\Commission\DataLoaders;

use App\Components\Commission\DataObject;
use App\Components\Commission\DataTransformer;
use App\Components\Commission\Exceptions\ValidationException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class FileDataLoader implements DataLoaderInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly string $filePath,
        private readonly DataTransformer $dataTransformer,
        ?LoggerInterface $logger,
    ) {
        $this->setLogger($logger ?: new NullLogger());
    }

    /**
     * @return DataObject[]
     */
    public function load(): array
    {
        $dtos = [];
        $rows = explode(PHP_EOL, file_get_contents($this->filePath));
        foreach ($rows as $row) {
            try {
                $dtos[] = $this->dataTransformer->transformArrayToObject((array)json_decode($row));
            } catch (ValidationException $exception) {
                $this->logger->error('Row ' . $row . ': ' . $exception->getMessage());
            }
        }

        return $dtos;
    }
}