<?php

namespace MutovSlingr\Console\Command;

use MutovSlingr\Loader\TemplateLoader;
use MutovSlingr\Model\Api;
use MutovSlingr\Processor\TemplateProcessor;
use Slim\Interfaces\CollectionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ApiCommand extends Command
{

    const OUTPUT_FOLDER = '/var/www/mutov-slingr/app/var/out';

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var CollectionInterface
     */
    private $config;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * ApiCommand constructor.
     * @param Api $api
     * @param CollectionInterface $config
     * @param string $name
     */
    public function __construct(Api $api, CollectionInterface $config, $name = null)
    {
        parent::__construct($name);

        $this->api = $api;
        $this->config = $config;
    }

    protected function configure()
    {
        $this
            ->setName('api:call')
            ->setDescription('Make call to generatedata API')
            ->addOption(
                'input-file',
                'i',
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
            ->addOption(
                'output-format',
                'f',
                InputOption::VALUE_OPTIONAL,
                'Output format (default: json; possible values: json, php)',
                'json'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $text = '';
        $fileInput = $input->getOption('input-file');
        $fileOutput = $input->getOption('output-file');
        $outputFormat = $input->getOption('output-format');

        if ($fileInput) {
            $fileInputPath = realpath($fileInput);
            if (file_exists($fileInputPath)) {
                $results = $this->processTemplateFile($fileInputPath);

                if (!is_null($results)) {
                    /** @todo unify output rendering for GUI and API */
                    $formattedResults = $this->render($results, $outputFormat);

                    if (strlen($formattedResults) > 0) {
                        $fileOutputPath = self::OUTPUT_FOLDER . DIRECTORY_SEPARATOR . $fileOutput;
                        file_put_contents($fileOutputPath, $formattedResults);
                        $text = '<info>Data has been written to ' . $fileOutputPath . '.</info>';
                    }
                }
            } else {
                $text = '<error>File ' . $fileInputPath . ' does not exist.</error>';
            }
        }

        $output->writeln($text);
    }

    /**
     * @param $fileInputPath
     * @return array|null
     */
    protected function processTemplateFile($fileInputPath)
    {
        $templateLoader = new TemplateLoader($this->config);
        $templateContent = $templateLoader->loadTemplate($fileInputPath);

        $templateProcessor = new TemplateProcessor($this->api, $this->config);

        return $templateProcessor->processTemplate($templateContent);
    }

    /**
     * @param mixed $results
     * @param string $outputFormat
     * @return string
     */
    private function render($results, $outputFormat)
    {
        $formattedResults = '';

        switch ($outputFormat) {
            case 'json':
                $formattedResults = json_encode($results);
                break;

            case 'php':
                $formattedResults = '<?php $data = ' . var_export($results, true) . ';';
                break;

            default:
                $this->output->writeln('<error>Invalid output format (' . $outputFormat . ').</error>');
                break;
        }

        return $formattedResults;
    }

}
