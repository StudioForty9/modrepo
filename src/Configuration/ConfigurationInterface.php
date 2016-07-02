<?php

namespace Modrepo\Configuration;

/**
 * Responsibility: To define the contract for obtaining configuration data from storage.
 *
 * @subpackage Modrepo\Configuration
 * @package    Modrepo
 */
interface ConfigurationInterface
{
    /**
     * Load the config file into memory.
     *
     * @return self
     */
    public function load();

    /**
     * Determine if the load method has been called and configuration is available.
     *
     * @return bool
     */
    public function isLoaded();

    /**
     * Set a configuration value.
     *
     * @param  string $key
     * @param  string $value
     * @return self
     */
    public function set($key, $value);

    /**
     * Get a configuration value.
     *
     * @param  string $key
     * @param  string $default
     * @return string
     */
    public function get($key, $default = '');

    /**
     * Set the configuration data.
     *
     * @param  array $data
     * @return self
     */
    public function setConfig(array $data);

    /**
     * Get the configuration data.
     *
     * @return array
     */
    public function getConfig();

    /**
     * Set the filepath.
     *
     * @param string $filepath
     *
     * @return self
     */
    public function setFilepath($filepath);

    /**
     * Get the filepath.
     *
     * @return string
     */
    public function getFilepath();

    /**
     * Save the configuration data.
     *
     * @return self
     */
    public function save();
}
