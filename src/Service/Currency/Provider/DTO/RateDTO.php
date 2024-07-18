<?php declare(strict_types=1);

namespace App\Service\Currency\Provider\DTO;

use App\Service\Currency\ValueObject\CurrencyCode;

readonly class RateDTO
{
    public function __construct(
        public CurrencyCode $baseCurrencyCode,
        public CurrencyCode $targetCurrencyCode,
        public float $rate,
    ) {
    }
}
