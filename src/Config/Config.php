<?php

namespace App\Config;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Config
{
    private const KEY_DEBUG = 'debug';
    private const KEY_TEMPLATE_DATA = 'template_data';
    private const KEY_TEMPLATE_DIRECTORIES = 'template_directories';

    /** @var array|null */
    private static $config = null;

    public function isDebug(): bool
    {
        return (bool) $this->getConfigValue(self::KEY_DEBUG);
    }

    public function getTemplateData(): array
    {
        return (array) $this->getConfigValue(self::KEY_TEMPLATE_DATA);
    }

    public function getTemplateDirectories(): array
    {
        return (array) $this->getConfigValue(self::KEY_TEMPLATE_DIRECTORIES);
    }

    private function getConfigValue(string $key)
    {
        return $this->getConfig()[$key];
    }

    private function getConfig(): array
    {
        if (self::$config !== null) {
            return self::$config;
        }

        $distPath = $this->getConfigFilePath('config.json.dist');
        $customPath = $this->getConfigFilePath('config.json');

        $config = $this->unserialize(file_get_contents($distPath));

        if (file_exists($customPath)) {
            $customConfig = $this->unserialize(file_get_contents($customPath));
            $config = array_merge($config, $customConfig);
        }

        return self::$config = $this->validate($config);
    }

    private function getConfigFilePath(string $file): string
    {
        return ROOT_PATH . '/config/' . $file;
    }

    private function unserialize(string $json): array
    {
        $result = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                sprintf("Failed to read config: %s", json_last_error_msg())
            );
        }

        return $result;
    }

    private function validate(array $config): array
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setRequired([
            self::KEY_DEBUG,
            self::KEY_TEMPLATE_DATA,
            self::KEY_TEMPLATE_DIRECTORIES,
        ]);

        $optionsResolver->setAllowedTypes(self::KEY_DEBUG, 'bool');
        $optionsResolver->setAllowedTypes(self::KEY_TEMPLATE_DATA, 'array');
        $optionsResolver->setAllowedTypes(self::KEY_TEMPLATE_DIRECTORIES, 'string[]');

        return $optionsResolver->resolve($config);
    }
}
