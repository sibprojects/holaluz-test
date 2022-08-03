<?php

namespace App\Luz\Application\Command;

use App\Luz\Domain\Processor\Processor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class ReadCommand extends Command
{
    protected function configure()
    {
        $this->setName('app:read');
        $this->setDescription('Read electricity information from file');

        $this->addArgument(
            'file',
            InputArgument::REQUIRED,
            'Select file with full path'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filename = $input->getArgument('file');
        if(!file_exists($filename)) {
            $output->writeln('<error>File not found!</error>');
            return Command::FAILURE;
        }

        $processor = new Processor($filename);
        $data = $processor->do();

        if(count($data)) {
            $table = new Table($output);
            $table
                ->setHeaders(['Client', 'Month', 'Suspicious', 'Median'])
                ->setRows($data);
            $table->render();
        } else {
            $output->writeln('<info>All reading in file is correct!</info>');
        }

        try {

        } catch (\Throwable $e) {
            $output->writeln('<error>Error reading file!</error>');
        }

        return Command::SUCCESS;
    }
}
