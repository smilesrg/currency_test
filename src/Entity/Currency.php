<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\CurrencyRepository;
use App\Service\Currency\ValueObject\CurrencyCode;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[UniqueEntity(
    fields: ['code'],
    message: 'This currency already exists.',
    errorPath: 'code',
)]
class Currency
{
    #[ORM\Id]
    #[ORM\Column(length: 3)]
    #[Assert\Currency]
    private string $code;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 2, max: 255)]
    private string $name;

    #[ORM\Column(length: 3)]
    #[Assert\Length(min: 1, max: 3)]
    private string $symbol;

    public function __construct(CurrencyCode $currencyCode, string $name, string $symbol)
    {
        $this->code = $currencyCode->getValue();
        $this->name = $name;
        $this->symbol = $symbol;
    }

    public function __toString(): string
    {
        return $this->getCode()->getValue();
    }

    public function getCode(): CurrencyCode
    {
        return new CurrencyCode($this->code);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setCode(string $code): Currency
    {
        $this->code = $code;

        return $this;
    }

    public function setName(string $name): Currency
    {
        $this->name = $name;

        return $this;
    }

    public function setSymbol(string $symbol): Currency
    {
        $this->symbol = $symbol;

        return $this;
    }
}
