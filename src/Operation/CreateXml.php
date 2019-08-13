<?php

namespace App\Operation;

use App\Config\Config;
use App\Service\ProjectDirectory;
use App\Service\Renderer;

class CreateXml
{
    /** @var ProjectDirectory */
    private $projectDirectory;

    /** @var Config */
    private $config;

    /** @var Renderer */
    private $renderer;

    /** @var string */
    private $file;

    /** @var string */
    private $xsd;

    public function __construct(
        ProjectDirectory $projectDirectory,
        Config $config,
        Renderer $renderer,
        string $file,
        string $xsd
    ) {
        $this->projectDirectory = $projectDirectory;
        $this->config = $config;
        $this->renderer = $renderer;
        $this->file = $file;
        $this->xsd = $xsd;
    }

    public function execute(string $name): void
    {
        if (!is_dir($this->projectDirectory->getModulePath($name))) {
            throw new \RuntimeException(sprintf("The module %s does not exist.", $name));
        }

        $path = $this->projectDirectory->getModulePath($name, $this->file);

        $this->renderer->createAndRender($path, 'config.xml.twig', [
            'data' => $this->config->getTemplateData(),
            'xsd' => $this->xsd,
        ]);
    }
}
