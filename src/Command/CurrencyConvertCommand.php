<?php declare(strict_types=1);

namespace App\Command;

use App\Service\Currency\Converter\Converter;
use App\Service\Currency\Converter\Exception\ConverterException;
use App\Service\Currency\ValueObject\CurrencyCode;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:currency:convert', description: 'Convert currency')]
class CurrencyConvertCommand extends Command
{
    public function __construct(
        private readonly Converter $converter,
    ){
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('amount', InputArgument::REQUIRED, 'Amount')
            ->addArgument('baseCurrency', InputArgument::REQUIRED, 'Base currency')
            ->addArgument('targetCurrency', InputArgument::REQUIRED, 'Target currency')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $amount = (int)$input->getArgument('amount');
        $baseCurrency = $input->getArgument('baseCurrency');
        $targetCurrency = $input->getArgument('targetCurrency');

        $baseCurrency = new CurrencyCode($baseCurrency);
        $targetCurrency = new CurrencyCode($targetCurrency);

        try {
            $amount = $this->converter->convert($amount, $baseCurrency, $targetCurrency);
        } catch (ConverterException $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }

        $output->writeln(number_format($amount, 2));

        return Command::SUCCESS;
    }
}
