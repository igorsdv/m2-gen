<?php

namespace App\Console;

use App\Operation\CreateModule;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateModuleCommand extends AbstractGenerateCommand
{
    protected static $defaultName = 'generate:module';

    protected $description = 'Generates the files required for a module';

    /** @var CreateModule */
    private $createModule;

    public function __construct(CreateModule $createModule)
    {
        parent::__construct();

        $this->createModule = $createModule;
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $moduleName = $this->getModuleName($input);
        $this->createModule->execute($moduleName);

        $output->writeln(sprintf('Created module %s', $moduleName));
    }
}
