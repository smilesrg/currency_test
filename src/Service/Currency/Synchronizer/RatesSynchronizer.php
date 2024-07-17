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

readonly class RatesSynchronizer
{
    public function __construct(
        private RateProviderInterface $adapter,
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
            $currencyList[$currency->getCode()] = $currency;
        }
        unset($currency);

        foreach ($currencyList as $baseCurrency) {
            $rates = $this->adapter->getRates(
                new CurrencyCode($baseCurrency->getCode()),
                $this->transformToValueObjects($currencyList)
            );
            $this->rateRepository->beginTransaction();
            try {
                $this->rateRepository->deleteRatesForBaseCurrency($baseCurrency);
                foreach ($rates as $rateDTO) {
                    $targetCurrency = $currencyList[$rateDTO->targetCurrencyCode->getValue()];
                    if ($baseCurrency->getCode() === $targetCurrency->getCode()) {
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

    /**
     * @param Currency[] $currencyList
     * @return CurrencyCode[]
     */
    private function transformToValueObjects(array $currencyList): array
    {
        $result = [];
        foreach ($currencyList as $currency) {
            $result[] = new CurrencyCode($currency->getCode());
        }

        return $result;
    }
}
