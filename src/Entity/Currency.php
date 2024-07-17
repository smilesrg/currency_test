<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\CurrencyRepository;
use App\Service\Currency\ValueObject\CurrencyCode;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\Column(length: 3)]
    private string $code;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 3)]
    private string $symbol;

    public function __construct(string $code, string $name, string $symbol)
    {
        $this->code = $code;
        $this->name = $name;
        $this->symbol = $symbol;
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
}
