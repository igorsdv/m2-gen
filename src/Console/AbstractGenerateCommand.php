<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class AbstractGenerateCommand extends Command
{
    protected const ARGUMENT_MODULE_NAME = 'module_name';

    protected $description = '';

    public function configure(): void
    {
        $this
            ->setDescription($this->description)
            ->addArgument(
                self::ARGUMENT_MODULE_NAME,
                InputArgument::REQUIRED,
                'The name of the module as Vendor_Module'
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument(self::ARGUMENT_MODULE_NAME) === null) {
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $question = new Question('Module name: ');
            $answer = $helper->ask($input, $output, $question);

            $input->setArgument(self::ARGUMENT_MODULE_NAME, $answer);
        }
    }

    protected function getModuleName(InputInterface $input)
    {
        return $input->getArgument(self::ARGUMENT_MODULE_NAME);
    }
}
