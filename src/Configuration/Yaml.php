<?php

namespace Modrepo\Configuration;

use ErrorException;
use Symfony\Component\Yaml\Yaml as YamlParser;

/**
 * Responsibility: To implement the YAML strategy for obtaining configuration data.
 *
 * @subpackage Modrepo\Configuration
 * @package    Modrepo
 */
class Yaml implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $filepath;

    /**
     * @var array
     */
    private $config;

    /**
     * Configuration constructor.
     *
     * @param string $filepath
     */
    public function __construct($filepath)
    {
        $this->setFilepath($filepath);
    }

    /**
     * Load the config file into memory.
     *
     * @return self
     * @throws \Modrepo\Configuration\NotFoundException
     */
    public function load()
    {
        if (!is_file($this->filepath)) {
            throw new NotFoundException();
        }

        $config = @file_get_contents($this->filepath);
        $this->setConfig(YamlParser::parse($config));

        return $this;
    }

    /**
     * Determine if the load method has been called and configuration is available.
     *
     * @return bool
     */
    public function isLoaded()
    {
        return !empty($this->config);
    }

    /**
     * Set a configuration value.
     *
     * @param  string $key
     * @param  string $value
     * @return self
     */
    public function set($key, $value)
    {
        $this->config[$key] = $value;

        return $this;
    }

    /**
     * Get a configuration value.
     *
     * @param  string $key
     * @param  string $default
     *
     * @return string
     */
    public function get($key, $default = '')
    {
        if (!isset($this->config[$key])) {
            return $default;
        }

        return $this->config[$key];
    }

    /**
     * Set the configuration data.
     *
     * @param  array $data
     * @return self
     */
    public function setConfig(array $data)
    {
        $this->config = $data;
        return $this;
    }

    /**
     * Get the configuration data.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the filepath.
     *
     * @param  string $filepath
     * @return self
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;

        return $this;
    }

    /**
     * Get the filepath.
     *
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Save the configuration data.
     *
     * @return self
     * @throws NotFoundException
     * @throws ErrorException
     */
    public function save()
    {
        $data = YamlParser::dump($this->config);

        if (! @file_put_contents($this->filepath, $data)) {
            throw $this->transformErrorToException(error_get_last());
        }
    }

    /**
     * @return \ErrorException
     */
    private function transformErrorToException($error)
    {
        return new ErrorException(
            $error['message'], 0, $error['type'], $error['file'], $error['line']
        );
    }
}
