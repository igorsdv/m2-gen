<?php

namespace App\Service;

class ProjectDirectory
{
    /** @var string|null */
    private $baseDirectory = null;

    public function getPath(string $path = ''): string
    {
        return $this->getAbsolutePath($this->getBaseDirectory(), $path);
    }

    public function getModulePath(string $name, string $path = ''): string
    {
        if (!preg_match('/^[A-Za-z0-9]+_[A-Za-z0-9]+$/', $name)) {
            throw new \RuntimeException('The module name is invalid.');
        }

        $modulePath = $this->getPath('app/code/' . str_replace('_', '/', $name));

        return $this->getAbsolutePath($modulePath, $path);
    }

    private function getBaseDirectory(): string
    {
        if ($this->baseDirectory !== null) {
            return $this->baseDirectory;
        }

        for ($dir = getcwd(); !is_dir("$dir/app/code"); $dir = dirname($dir)) {
            if ($dir === '/') {
                throw new \RuntimeException("Could not find Magento base directory.");
            }
        }

        return $this->baseDirectory = $dir;
    }

    private function getAbsolutePath(string $basePath, string $relativePath): string
    {
        return rtrim($basePath . '/' . $relativePath, '/');
    }
}
