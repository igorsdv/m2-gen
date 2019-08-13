<?php

namespace App\Operation;

use App\Config\Config;
use App\Service\ProjectDirectory;
use App\Service\Renderer;

class CreateModule
{
    /** @var ProjectDirectory */
    private $projectDirectory;

    /** @var Config */
    private $config;

    /** @var Renderer */
    private $renderer;

    public function __construct(
        ProjectDirectory $projectDirectory,
        Config $config,
        Renderer $renderer
    ) {
        $this->projectDirectory = $projectDirectory;
        $this->config = $config;
        $this->renderer = $renderer;
    }

    public function execute(string $name): void
    {
        $data = [
            'data' => $this->config->getTemplateData(),
            'module_name' => $name,
        ];

        $path = $this->projectDirectory->getModulePath($name, 'etc/module.xml');
        $this->renderer->createAndRender($path, 'module.xml.twig', $data);

        $path = $this->projectDirectory->getModulePath($name, 'registration.php');
        $this->renderer->render($path, 'registration.php.twig', $data);
    }
}
