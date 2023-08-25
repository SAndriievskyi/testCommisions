<?php

namespace App\Commands;

use App\Components\Commission\DataLoaders\Factory;
use App\Components\Commission\DataLoaders\FileDataLoader;
use App\Components\Commission\ComissionCalculator;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FileCommissionCalculation extends Command
{
    public function __construct(
        private readonly ComissionCalculator $comissionCalculator,
        private readonly Factory $factory,
        string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('app:calculate')
            ->setDescription('Calculate comission by file')
            ->addOption(
                'file_path',
                '-f',
                InputOption::VALUE_OPTIONAL,
                'File path',
            )->addOption(
                'precision',
                '-p',
                InputOption::VALUE_OPTIONAL,
                'precision',
                null,
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getOption('file_path');
        $precision = $input->getOption('precision');
        $dataLoader = $this->factory->createFileDataLoader($filePath);
        foreach ($dataLoader->load() as $dataObject) {
            try {
                $calculatedComissions = $this->comissionCalculator->calculate($dataObject, $precision);
            } catch (Exception $exception) {
                $calculatedComissions = 'Error: ' . $exception->getMessage();
            }

            $output->writeln($calculatedComissions);
        }

        return self::SUCCESS;
    }
}
