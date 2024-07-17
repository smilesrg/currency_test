<?php declare(strict_types=1);

namespace App\Service\Currency\ValueObject;

use Symfony\Component\Intl\Currencies;
use UnexpectedValueException;

readonly class CurrencyCode
{
    /**
     * @param string $currencyCode ISO3 Currency Code
     */
    public function __construct(
        private string $currencyCode
    ) {
        if (!Currencies::exists($currencyCode)) {
            throw new UnexpectedValueException(sprintf('Invalid currency: %s', $this->currencyCode));
        }
    }

    public function getValue(): string
    {
        return $this->currencyCode;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
