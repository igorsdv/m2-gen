<?php

namespace App\Console;

use App\Operation\CreateXml;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateEventsCommand extends AbstractGenerateCommand
{
    protected static $defaultName = 'generate:events';

    protected $description = 'Generates the events.xml file for a module';

    /** @var CreateXml */
    private $createXml;

    public function __construct(CreateXml $createXml)
    {
        parent::__construct();

        $this->createXml = $createXml;
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $moduleName = $this->getModuleName($input);
        $this->createXml->execute($moduleName);

        $output->writeln(sprintf('Created events.xml file for module %s', $moduleName));
    }
}
