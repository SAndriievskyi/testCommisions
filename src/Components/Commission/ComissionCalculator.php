<?php

namespace App\Components\Commission;

use App\Components\Bin\Factory as BinFactory;
use App\Components\Commission\Exceptions\DataNotRecivedException;
use App\Components\ExchangeRate\Factory as RateFactory;
use App\Enums\Currency;
use App\Enums\EuropCountryCode;

class ComissionCalculator
{
    private const EUROP_COUNTRY_COMISSION = 0.01;
    private const NONE_EUROP_COUNTRY_COMISSION = 0.02;

    public function __construct(
        private readonly RateFactory $rateFactory,
        private readonly BinFactory $binFactory,
    ) {}

    public function calculate(DataObject $dataObject, ?int $precision = null): float
    {
        $binData = $this->binFactory->retrieveBin($dataObject->getBin());
        if (!$binData) {
            throw new DataNotRecivedException('Bin not exist');
        }

        $currency = $dataObject->getCurrency();
        $rates = $this->rateFactory->retrieveRates();
        $rate = $rates[$currency->value] ?? 0;
        if ($currency === Currency::EUR || !$rate) {
            $amntFixed = $dataObject->getAmount();
        } else {
            $amntFixed = $dataObject->getAmount() / $rate;
        }
        $isEuropCountry = EuropCountryCode::tryFrom($binData->getCountryAlpha2());
        $commision = $amntFixed * ($isEuropCountry ? self::EUROP_COUNTRY_COMISSION : self::NONE_EUROP_COUNTRY_COMISSION);

        return $precision ? round($commision, $precision) : $commision;
    }
}