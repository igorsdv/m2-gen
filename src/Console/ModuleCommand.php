<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleCommand extends Command
{
    protected static $defaultName = 'module';

    public function configure(): void
    {
        $this->setDescription('Generates the files required for a module');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('Module command executed.');
    }
}
