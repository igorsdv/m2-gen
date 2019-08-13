<?php

namespace App\Twig;

use App\Config\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

class LoaderFactory
{
    /** @var Config */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function create(): LoaderInterface
    {
        $templateDirectories = array_merge(
            [ROOT_PATH . '/templates'],
            $this->config->getTemplateDirectories()
        );

        return new FilesystemLoader($templateDirectories);
    }
}
