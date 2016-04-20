<?php

namespace MutovSlingr\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ApiCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('api:call')
            ->setDescription('Make call to generatedata API')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'Specify the file containing the template structure'
            )
            ->addOption(
                'yell',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text ='';
        $file = $input->getArgument('file');

        if ($file) {
            if (file_exists($file)) {
                $output->writeln('<info>File ' . $file . ' exists.</info>');

                /** @todo add call for api service here */
            }
            else
            {
                $text = '<error>File ' . $file . ' does not exist.</error>';
            }
        }

        $output->writeln($text);
    }

}
