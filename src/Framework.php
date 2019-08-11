<?php

namespace App;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Framework
{
    public function getContainer(): ContainerInterface
    {
        $containerCacheFile = $this->getCacheDir() . '/ServiceContainer.php';
        $containerClassName = 'ServiceContainer';

        $isDebug = (bool) getenv('APP_DEBUG');

        if (!$isDebug && file_exists($containerCacheFile)) {
            require $containerCacheFile;
            return new $containerClassName();
        } else {
            $containerBuilder = new ContainerBuilder();

            $fileLocator = new FileLocator($this->getConfigDir());
            $loader = new YamlFileLoader($containerBuilder, $fileLocator);
            $loader->load('services.yaml');

            $containerBuilder->compile();

            if (!$isDebug) {
                if (!is_dir($this->getCacheDir())) {
                    mkdir($this->getCacheDir(), 0755, true);
                }

                $dumper = new PhpDumper($containerBuilder);
                file_put_contents($containerCacheFile, $dumper->dump([
                    'class' => $containerClassName,
                ]));
            }

            return $containerBuilder;
        }
    }

    private function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    private function getCacheDir(): string
    {
        return $this->getProjectDir() . '/cache';
    }

    private function getConfigDir(): string
    {
        return $this->getProjectDir() . '/config';
    }
}
