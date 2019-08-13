<?php

namespace App\Console;

use App\Operation\CreateXml;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateXmlCommand extends AbstractGenerateCommand
{
    /** @var CreateXml */
    private $createXml;

    /** @var string */
    private $file;

    public function __construct(CreateXml $createXml, string $name, string $file)
    {
        parent::__construct($name);

        $this->createXml = $createXml;
        $this->file = $file;
    }

    public function configure(): void
    {
        $this->setDescription("Generates the {$this->file} file for a module");

        parent::configure();
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $moduleName = $this->getModuleName($input);
        $this->createXml->execute($moduleName);

        $output->writeln("Created {$this->file} file for module $moduleName");
    }
}
