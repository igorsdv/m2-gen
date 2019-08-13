<?php

namespace App\DependencyInjection;

use App\Config\Config;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContainerFactory
{
    public static function create(): ContainerInterface
    {
        $cacheDir = ROOT_PATH . '/cache';
        $containerClassName = 'ServiceContainer';
        $containerCacheFile = "$cacheDir/$containerClassName.php";

        $isDebug = (new Config())->isDebug();

        if (!$isDebug && file_exists($containerCacheFile)) {
            require $containerCacheFile;
            return new $containerClassName();
        } else {
            $configDir = ROOT_PATH . '/config';
            $containerBuilder = new ContainerBuilder();

            $fileLocator = new FileLocator($configDir);
            $loader = new YamlFileLoader($containerBuilder, $fileLocator);
            $loader->load('services.yaml');

            $containerBuilder->compile();

            if (!$isDebug) {
                if (!is_dir($cacheDir)) {
                    mkdir($cacheDir, 0755, true);
                }

                $dumper = new PhpDumper($containerBuilder);
                file_put_contents($containerCacheFile, $dumper->dump([
                    'class' => $containerClassName,
                ]));
            }

            return $containerBuilder;
        }
    }
}
