<?php declare(strict_types=1);

namespace App\Service\Currency\Provider\Bridge\Freecurrency\Provider;

use App\Service\Currency\Provider\Bridge\Freecurrency\Client\Client;
use App\Service\Currency\Provider\Bridge\Freecurrency\Client\Exception\ClientException;
use App\Service\Currency\Provider\DTO\RateDTO;
use App\Service\Currency\Provider\Exception\RateProviderException;
use App\Service\Currency\Provider\RateProviderInterface;
use App\Service\Currency\ValueObject\CurrencyCode;

readonly class FreeCurrencyRateProvider implements RateProviderInterface
{
    public function __construct(protected Client $client)
    {
    }

    public function getRates(CurrencyCode $baseCurrencyCode, array $targetCurrencyCodes): iterable
    {
        try {
            $rates = $this->client->getRates(
                $baseCurrencyCode->getValue(),
                $this->extractValues($targetCurrencyCodes)
            );
        } catch (ClientException $exception) {
            throw new RateProviderException($exception->getMessage(), $exception->getCode(), $exception);
        }

        //TODO: validate using JSON Schema, if the data received is correct

        foreach ($rates['data'] as $currencyCode => $rate) {
            yield new RateDTO($baseCurrencyCode, new CurrencyCode($currencyCode), $rate);
        }
    }

    private function extractValues(array $targetCurrencyCodes): array
    {
        $result = [];
        foreach ($targetCurrencyCodes as $currencyCode) {
            $result[] = $currencyCode->getValue();
        }

        return $result;
    }
}
