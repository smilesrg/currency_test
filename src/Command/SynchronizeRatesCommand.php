<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Currency\Synchronizer\RateSynchronizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:synchronize:rates', description: 'Synchronize all currencies rates')]
class SynchronizeRatesCommand extends Command
{
    public function __construct(
        private readonly RateSynchronizer $ratesSynchronizer,
    ){
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->ratesSynchronizer->synchronize();

        $output->writeln('Rates synchronized!');

        return Command::SUCCESS;
    }
}
