<?php declare(strict_types=1);

namespace App\Service\Currency\Converter;

use App\Repository\RateRepository;
use App\Service\Currency\Converter\Exception\ConverterException;
use App\Service\Currency\ValueObject\CurrencyCode;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

readonly class Converter
{
    public function __construct(
        private RateRepository $rateRepository
    ) {
    }

    /**
     * @throws ConverterException
     */
    public function convert(int $amount, CurrencyCode $baseCurrency, CurrencyCode $targetCurrency): float
    {
        try {
            $rate = $this->rateRepository->findRate($baseCurrency, $targetCurrency);
        } catch (NonUniqueResultException|NoResultException $exception) {
            throw new ConverterException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return $rate->getRate() * $amount;
    }
}
