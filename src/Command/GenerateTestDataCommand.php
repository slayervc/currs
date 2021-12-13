<?php
declare(strict_types=1);

namespace App\Command;


use App\Entity\CurrencyRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateTestDataCommand extends Command
{
    protected static $defaultName = 'data:generate';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('pair', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pair = $input->getArgument('pair');
        [$base, $quote] = explode('/', $pair);

        //TODO вынести эти параметры в опции
        $periodDays = 5;
        $secondsLeft = $periodDays * 24 * 60 * 60;
        $rangeFromSecs = 5;
        $rangeToSecs = 10;
        $datetime = new \DateTimeImmutable();
        $counter = 0;
        $batchSize = 20;

        while ($secondsLeft >= 0) {
            $rate = (new CurrencyRate())
                ->setBase($base)
                ->setQuote($quote)
                ->setRate(rand(10000, 1000000) / 10000)
                ->setDatetime($datetime);

            $this->em->persist($rate);
            $range = rand($rangeFromSecs, $rangeToSecs);
            $datetime = $datetime->sub(new \DateInterval('PT' . $range . 'S'));
            $secondsLeft -= $range;
            $counter++;

            if (0 === ($counter % $batchSize)) {
                $this->em->flush();
                $this->em->clear();
            }
        }

        $this->em->flush();
        $this->em->clear();
        $output->writeln($counter . ' records added');

        return 0;
    }
}