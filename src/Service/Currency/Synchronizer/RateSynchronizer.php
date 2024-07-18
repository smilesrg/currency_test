<?php declare(strict_types=1);

namespace App\Service\Currency\Synchronizer;

use App\Entity\Currency;
use App\Entity\Rate;
use App\Repository\CurrencyRepository;
use App\Repository\RateRepository;
use App\Service\Currency\Provider\RateProviderInterface;
use App\Service\Currency\Synchronizer\Exception\SynchronizerException;
use App\Service\Currency\ValueObject\CurrencyCode;
use Psr\Log\LoggerInterface;

readonly class RateSynchronizer
{
    public function __construct(
        private RateProviderInterface $rateProvider,
        private CurrencyRepository $currencyRepository,
        private RateRepository $rateRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function synchronize(): void
    {
        $currencies = $this->currencyRepository->findAll();

        $currencyList = [];
        foreach ($currencies as $currency) {
            $currencyList[$currency->getCode()->getValue()] = $currency;
        }
        unset($currency);

        $currencyCodes = array_map(fn(Currency $currency): CurrencyCode => $currency->getCode(), $currencyList);

        foreach ($currencyList as $baseCurrency) {
            $rates = $this->rateProvider->getRates($baseCurrency->getCode(), $currencyCodes);
            $this->rateRepository->beginTransaction();
            try {
                $this->rateRepository->deleteRatesForBaseCurrency($baseCurrency);
                foreach ($rates as $rateDTO) {
                    $targetCurrency = $currencyList[$rateDTO->targetCurrencyCode->getValue()];
                    if ($baseCurrency->getCode()->equals($targetCurrency->getCode())) {
                        continue;
                    }
                    $rate = new Rate($baseCurrency, $targetCurrency, $rateDTO->rate);
                    $this->rateRepository->persist($rate);
                }
                $this->rateRepository->flush();
                $this->rateRepository->commit();
            } catch (\Throwable $exception) {
                $this->logger->error($exception->getMessage(), ['exception' => $exception]);
                $this->rateRepository->rollback();

                throw new SynchronizerException($exception);
            }
        }
    }
}
