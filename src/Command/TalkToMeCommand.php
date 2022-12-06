<?php

namespace App\Command;

use App\Service\MixRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:talk-to-me',
    description: 'A self-aware command that can do... only one thing.',
)]
class TalkToMeCommand extends Command
{
    public function __construct(private MixRepository $mixRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Your Name')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Shall I YELL ?!');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name') ?: 'whoever you are !';
        $shouldYell = $input->getOption('yell');

        $msg = sprintf('Hey %s !', $name);
        if ($shouldYell) {
            $msg = strtoupper($msg);
        }

        $io->success($msg);

        if ($io->confirm('Do you want a mix recommandation ?')) {
            $mixes = $this->mixRepository->findAll();
            $mix = $mixes[array_rand($mixes)];
            $io->note('I recommand the mix : ' . $mix['title']);
        }

        return Command::SUCCESS;
    }
}
