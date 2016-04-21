<?php

namespace MutovSlingr\Console\Command;

use MutovSlingr\Processor\TemplateProcessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ApiCommand extends Command
{

    const OUTPUT_FOLDER = '/var/www/mutov-slingr/app/var/out';

    protected function configure()
    {
        $this
            ->setName('api:call')
            ->setDescription('Make call to generatedata API')
            ->addOption(
                'input-file',
                'f',
                InputOption::VALUE_REQUIRED,
                'File containing the template structure'
            )
            ->addOption(
                'output-file',
                'o',
                InputOption::VALUE_OPTIONAL,
                'Output file (default: out.txt)',
                'out.txt'
            )
//            ->addArgument(
//                'file',
//                InputArgument::REQUIRED,
//                'Specify the file containing the template structure'
//            )
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
        $fileInput = $input->getOption('input-file');
        $fileOutput = $input->getOption('output-file');

        if ($fileInput) {
            $fileInputPath = realpath($fileInput);
            if (file_exists($fileInputPath)) {
                $output->writeln('<info>File ' . $fileInputPath . ' exists.</info>');

                /** @todo add call for api service here */
                $processor = new TemplateProcessor();
                $results = $processor->processTemplate($fileInputPath);

                if ($results !== false) {
                    $fileOutputPath = self::OUTPUT_FOLDER . DIRECTORY_SEPARATOR. $fileOutput;
                    file_put_contents($fileOutputPath, $results);
                }
            }
            else
            {
                $text = '<error>File ' . $fileInputPath . ' does not exist.</error>';
            }
        }

        $output->writeln($text);
    }

}
