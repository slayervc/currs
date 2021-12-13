<?php
declare(strict_types=1);

namespace App\Command;


use App\DTO\Currency\CurrencyPair;
use App\Repository\CurrencyRate\CurrencyRateReadRepositoryInterface;
use App\Repository\CurrencyRate\CurrencyRateWriteRepositoryInterface;
use App\Repository\CurrencyRate\Exception\CurrencyRateReadRepositoryException;
use App\Repository\CurrencyRate\Exception\CurrencyRateWriteRepositoryException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PullCurrentRatesCommand extends Command
{
    protected static $defaultName = 'rates:pull_current';
    private CurrencyRateReadRepositoryInterface $readRepository;
    private CurrencyRateWriteRepositoryInterface $writeRepository;

    public function __construct(
        CurrencyRateReadRepositoryInterface $readRepository,
        CurrencyRateWriteRepositoryInterface $writeRepository
    ) {
        parent::__construct();
        $this->readRepository = $readRepository;
        $this->writeRepository = $writeRepository;
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('pairs', InputArgument::REQUIRED);
        $this->addArgument('datetime', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $datetime = new \DateTimeImmutable($input->getArgument('datetime') ?? 'now');
        $pairNames = explode(',', $input->getArgument('pairs'));
        $pairs = [];

        foreach ($pairNames as $pairName) {
            [$base, $quote] = explode('/', $pairName);
            $pairs[] = new CurrencyPair($base, $quote);
        }

        try {
            $ratesCollection = $this->readRepository->getCurrentRatesByCurrencyPairs($datetime, ...$pairs);
        } catch (CurrencyRateReadRepositoryException $e) {
            $output->writeln($e->getMessage());

            return 1;
        }

        try {
            $this->writeRepository->storeRates(...$ratesCollection->getAll());
        } catch (CurrencyRateWriteRepositoryException $e) {
            $output->writeln($e->getMessage());

            return 1;
        }

        return 0;
    }
}