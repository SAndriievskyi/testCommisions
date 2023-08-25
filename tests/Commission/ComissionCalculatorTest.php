<?php

namespace App\Tests\Commission;

use App\Components\Bin\DataObject as BinDataObject;
use App\Components\Bin\Factory as BinFactory;
use App\Components\Commission\ComissionCalculator;
use App\Components\Commission\DataObject as CommissionDataObject;
use App\Components\Commission\Exceptions\DataNotRecivedException;
use App\Components\ExchangeRate\Factory as RateFactory;
use App\Enums\Currency;
use Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ComissionCalculatorTest extends TestCase
{
    private MockObject|RateFactory $rateFactory;
    private MockObject|BinFactory $binFactory;

    /**
     * @dataProvider calculateData
     */
    public function testCalculate(
        CommissionDataObject $commissionDataObject,
        BinDataObject $binDataObject,
        $rates,
        float $expected,
    ) {
        $commissionCalculator = $this->createCommissionCalculator();

        $this->binFactory->expects(self::once())
            ->method('retrieveBin')
            ->with($commissionDataObject->getBin())
            ->willReturn($binDataObject);

        $this->rateFactory->expects(self::once())
            ->method('retrieveRates')
            ->willReturn($rates);

        $actual = $commissionCalculator->calculate($commissionDataObject, 2);

        self::assertEquals($expected, $actual);
    }

    public function calculateData(): Generator
    {
        yield 'EUR currency eurozone' => [
            'commissionDataObject' => new CommissionDataObject('12345678', '100', Currency::EUR),
            'binDataObject' => new BinDataObject('BE'),
            'rates' => ['EUR' => 1, 'GBP' => 1.2],
            'expected' => 1,
        ];
        yield 'EUR currency noneurozone' => [
            'commissionDataObject' => new CommissionDataObject('12345678', '100', Currency::EUR),
            'binDataObject' => new BinDataObject('UA'),
            'rates' => ['EUR' => 1, 'GBP' => 1.2],
            'expected' => 2,
        ];
        yield 'GBR currency eurozone' => [
            'commissionDataObject' => new CommissionDataObject('12345678', '100', Currency::GBP),
            'binDataObject' => new BinDataObject('BE'),
            'rates' => ['EUR' => 1, 'GBP' => 1.2],
            'expected' => 0.83,
        ];
        yield 'GBR currency noneurozone' => [
            'commissionDataObject' => new CommissionDataObject('12345678', '100', Currency::GBP),
            'binDataObject' => new BinDataObject('UA'),
            'rates' => ['EUR' => 1, 'GBP' => 1.2],
            'expected' => 1.67,
        ];
        yield 'don`t recive GBR currency' => [
            'commissionDataObject' => new CommissionDataObject('12345678', '100', Currency::GBP),
            'binDataObject' => new BinDataObject('UA'),
            'rates' => ['EUR' => 1],
            'expected' => 2,
        ];
    }

    public function testCalculateError()
    {
        $commissionCalculator = $this->createCommissionCalculator();
        $commissionDataObject = new CommissionDataObject('12345678', '100', Currency::GBP);

        $this->binFactory->expects(self::once())
            ->method('retrieveBin')
            ->willReturn(null);

        $this->rateFactory->expects(self::never())->method('retrieveRates');

        try {
            $commissionCalculator->calculate($commissionDataObject, 2);
        } catch (DataNotRecivedException $exception) {
            self::assertInstanceOf(DataNotRecivedException::class, $exception);
        }
    }

    private function createCommissionCalculator(): MockObject|ComissionCalculator
    {
        $this->rateFactory = $this->createMock(RateFactory::class);
        $this->binFactory = $this->createMock(BinFactory::class);

        return $this->getMockBuilder(ComissionCalculator::class)
            ->onlyMethods([])
            ->setConstructorArgs([
                $this->rateFactory,
                $this->binFactory,
            ])
            ->getMock();
    }
}