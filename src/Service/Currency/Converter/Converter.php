<?php declare(strict_types=1);

namespace App\Service\Currency\Converter;

use App\Repository\RateRepository;
use App\Service\Currency\ValueObject\CurrencyCode;

readonly class Converter
{
    public function __construct(
        private RateRepository $rateRepository
    ) {
    }

    public function convert(int $amount, CurrencyCode $baseCurrency, CurrencyCode $targetCurrency): int
    {
        $rate = $this->rateRepository->findRate($baseCurrency, $targetCurrency);

        return $amount*$rate;
    }
}
