<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use App\Service\Import\ImortUserDataFromCsvService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportUsersCommand extends Command
{
    private $usersImportService;
    protected static $defaultName = 'app:import:users';
    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct(ImortUserDataFromCsvService $usersImportService) {
        parent::__construct();

        $this->usersImportService = $usersImportService;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $importResults = $this->usersImportService->import();

        if ($importResults->hasSuccesses()) {
            $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
            foreach ($importResults->getSuccesses() as $successfullImport) {
                $io->success($successfullImport->getMessage());
            }
        }
        if ($importResults->hasErrors()) {
            $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
            foreach ($importResults->getErrors() as $errorImport) {
                $io->success($errorImport->getMessage());
            }
        }
        if (!$importResults->hasErrors() && !$importResults->hasErrors()) {
            $io->success('Nothing to create, is the csv file up to date');
        }


        return Command::SUCCESS;
    }
}
