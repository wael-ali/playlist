<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use App\Service\Import\ImportMp3DataFromCsvService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportMp3Command extends Command
{
    private $usersImportService;
    protected static $defaultName = 'app:import:mp3';
    protected static $defaultDescription = 'Add a short description for your command';

    public function __construct(ImportMp3DataFromCsvService $usersImportService) {
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
            foreach ($importResults->getSuccesses() as $successfullImport) {
                $io->success($successfullImport->getMessage());
            }
        }
        if ($importResults->hasErrors()) {
            foreach ($importResults->getErrors() as $errorImport) {
                $io->error($errorImport->getMessage());
            }
        }
        if (!$importResults->hasErrors() && !$importResults->hasErrors()) {
            $io->success('Nothing to create, is the csv file up to date');
        }
        $io->success('ImportMp3Command');


        return Command::SUCCESS;
    }
}
