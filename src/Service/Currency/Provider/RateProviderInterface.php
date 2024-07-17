<?php declare(strict_types=1);

namespace App\Service\Currency\Provider;

use App\Service\Currency\Provider\DTO\RateDTO;
use App\Service\Currency\ValueObject\CurrencyCode;

interface RateProviderInterface
{
    /**
     * @param CurrencyCode $baseCurrencyCode
     * @param CurrencyCode[] $targetCurrencyCodes
     * @return RateDTO[]
     */
    public function getRates(CurrencyCode $baseCurrencyCode, array $targetCurrencyCodes): iterable;
}
