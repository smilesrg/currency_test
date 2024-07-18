<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\RateRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RateRepository::class)]
class Rate
{
    #[ORM\Id]
    #[ORM\OneToOne]
    #[ORM\JoinColumn(name: 'base_currency', referencedColumnName: 'code', nullable: false)]
    private Currency $baseCurrency;

    #[ORM\Id]
    #[ORM\OneToOne]
    #[ORM\JoinColumn(name: 'target_currency', referencedColumnName: 'code', nullable: false)]
    private Currency $targetCurrency;

    #[ORM\Column]
    private ?float $rate;

    #[ORM\Column]
    private ?DateTimeImmutable $lastUpdated = null;

    public function __construct(Currency $baseCurrency, Currency $targetCurrency, float $rate)
    {
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->rate = $rate;
    }

    public function __toString(): string
    {
        return $this->baseCurrency->getCode() . '/' . $this->targetCurrency->getCode();
    }

    #[ORM\PreFlush]
    public function preFlush(): void
    {
        $this->lastUpdated = new DateTimeImmutable();
    }

    public function getBaseCurrency(): Currency
    {
        return $this->baseCurrency;
    }

    public function getTargetCurrency(): Currency
    {
        return $this->targetCurrency;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function getLastUpdated(): ?DateTimeImmutable
    {
        return $this->lastUpdated;
    }

    public function setRate(?float $rate): Rate
    {
        $this->rate = $rate;

        return $this;
    }

    public function setLastUpdated(?DateTimeImmutable $lastUpdated): Rate
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }
}
