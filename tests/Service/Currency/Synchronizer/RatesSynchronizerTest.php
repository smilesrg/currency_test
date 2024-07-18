<?php declare(strict_types=1);

namespace App\Tests\Service\Currency\Synchronizer;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use App\Repository\RateRepository;
use App\Service\Currency\Provider\DTO\RateDTO;
use App\Service\Currency\Provider\RateProviderInterface;
use App\Service\Currency\Synchronizer\RateSynchronizer;
use App\Service\Currency\ValueObject\CurrencyCode;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class RatesSynchronizerTest extends TestCase
{
    public function testSynchronize()
    {
        $usdCode = new CurrencyCode('USD');
        $plnCode = new CurrencyCode('PLN');

        $currencies = [];
        $currencies[] = new Currency($usdCode, 'United States Dollar', '$');
        $currencies[] = new Currency($plnCode, 'Polish Zloty', 'Zl');

        $rates = [];
        $rates[] = new RateDTO($usdCode, $plnCode, 4);
        $rates[] = new RateDTO($plnCode, $usdCode, 0.4);

        $rateProvider = $this->createMock(RateProviderInterface::class);
        $rateProvider
            ->expects($this->exactly(count($rates)))
            ->method('getRates')
            ->willReturnOnConsecutiveCalls(new \ArrayIterator([$rates[0]]), new \ArrayIterator([$rates[1]]));

        $currencyRepository = $this->createMock(CurrencyRepository::class);
        $currencyRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($currencies);

        $rateRepository = $this->createMock(RateRepository::class);
        $rateRepository
            ->expects($this->exactly(count($currencies)))
            ->method('beginTransaction');

        $rateRepository
            ->expects($this->exactly(count($rates)))
            ->method('persist');

        $rateRepository
            ->expects($this->exactly(count($currencies)))
            ->method('flush');

        $rateRepository
            ->expects($this->exactly(count($currencies)))
            ->method('commit');

        $logger = $this->createMock(LoggerInterface::class);

        $ratesSynchronizer = new RateSynchronizer($rateProvider, $currencyRepository, $rateRepository, $logger);

        $ratesSynchronizer->synchronize();
    }
}
