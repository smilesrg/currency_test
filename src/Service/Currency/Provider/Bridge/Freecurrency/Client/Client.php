<?php declare(strict_types=1);

namespace App\Service\Currency\Provider\Bridge\Freecurrency\Client;

use App\Service\Currency\Provider\Bridge\Freecurrency\Client\Exception\ClientException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class Client
{
    private const DEFAULT_TIMEOUT = 5;

    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire('%env(string:FREECURRENCY_API_BASEURL)%')]
        private string $baseUrl,
        #[Autowire('%env(string:FREECURRENCY_API_KEY)%')]
        private string $apiKey,
        #[Autowire('%env(string:FREECURRENCY_TIMEOUT)%')]
        private ?int $timeout = self::DEFAULT_TIMEOUT,
    ) {
    }

    /**
     * @param string $baseCurrency
     * @param string[] $targetCurrencies
     * @return array
     */
    public function getRates(string $baseCurrency, array $targetCurrencies): array
    {
        $options = [];
        $options['headers'] = $this->getHeaders();
        $options['timeout'] = $this->timeout;

        $query = http_build_query(['base_currency' => $baseCurrency, 'currencies' => implode(',', $targetCurrencies)]);

        try {
            $response = $this->httpClient->request(
                'GET',
                $this->baseUrl . '/latest?' . $query,
                $options,
            );

            return $response->toArray();
        } catch (HttpExceptionInterface|TransportExceptionInterface|DecodingExceptionInterface $exception) {
            throw new ClientException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    private function getHeaders(): array
    {
        return [
            'User-Agent' => 'Freecurrencyapi/custom',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'apikey' => $this->apiKey,
        ];
    }
}
