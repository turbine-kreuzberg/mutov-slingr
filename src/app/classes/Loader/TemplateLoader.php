<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 17:04
 */

namespace MutovSlingr\Loader;


use Slim\Interfaces\CollectionInterface;

class TemplateLoader
{

    private $config;

    /**
     * @param $config
     */
    public function __construct(CollectionInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param $templateFileName
     *
     * @return mixed|string
     * @throws \ErrorException
     */
    public function loadTemplate($templateFileName)
    {
        $fullPath = $this->getFullPathForTemplate($templateFileName);

        $contents = file_get_contents($fullPath);
        $contents = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \ErrorException(sprintf('Template parsing failed: %s', json_last_error_msg()));
        }

        return $contents;
    }

    /**
     * @param $templateFileName
     * @return bool
     * @throws \ErrorException
     */
    private function checkTemplateFileIsJsonFile($templateFileName)
    {
        if (strlen($templateFileName) === 0) {
            throw new \ErrorException('Template filename is empty.');
        }

        $filenameParts = explode('.', $templateFileName);

        if (strtolower(array_pop($filenameParts)) !== 'json') {
            throw new \ErrorException('Invalid template filename. Only json files are accepted.');
        }

        return true;
    }

    /**
     * @param $templateFileName
     * @return string
     * @throws \ErrorException
     */
    private function getFullPathForTemplate($templateFileName)
    {
        if ($this->checkTemplateFileIsJsonFile($templateFileName))
        {
            if (is_file($templateFileName)) {
                return $templateFileName;
            }

            $fullpath = $this->config->get('template_folder') . '/' . $templateFileName;

            if (is_file($fullpath)) {
                return $fullpath;
            }

            throw new \ErrorException(sprintf('Invalid template file. File does not seem to exist. Filename: %s, fullpath: %s', $templateFileName, $fullpath));
        }

        return '';
    }
}
