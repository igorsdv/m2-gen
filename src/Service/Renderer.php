<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

class Renderer
{
    /** @var Filesystem */
    private $fs;

    /** @var Environment */
    private $twig;

    public function __construct(Filesystem $fs, Environment $twig)
    {
        $this->fs = $fs;
        $this->twig = $twig;
    }

    public function createAndRender(string $path, string $template, array $data): void
    {
        if (file_exists($path)) {
            throw new \RuntimeException("$path already exists.");
        }

        $this->render($path, $template, $data);
    }

    public function render(string $path, string $template, array $data): void
    {
        $this->fs->dumpFile($path, $this->twig->render($template, $data));
    }
}
