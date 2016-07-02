<?php

namespace Modrepo\Console;

use Symfony\Component\Console\Application as Base;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Responsibility: To customize the application for our needs.
 *
 * @subpackage Modrepo\Console
 * @package    Modrepo
 */
class Application extends Base
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $configPath;

    /**
     * Set the path to the config file.
     *
     * @param string $path
     */
    public function setConfigPath($path)
    {
        $this->configPath = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrapContainer();
        $this->bootstrapCommands();

        return parent::doRun($input, $output);
    }

    /**
     * Get a list of services to bootstrap the container.
     *
     * @return array
     */
    private function getInitialServices()
    {
        return [
            'service.factory' => \Modrepo\Service\Factory::class
        ];
    }

    /**
     * Bootstrap the container.
     *
     * @return self
     */
    private function bootstrapContainer()
    {
        $this->container = new ContainerBuilder();

        $yaml = new Definition('Modrepo\Configuration\Yaml', [$this->configPath]);
        $this->container->setDefinition('config', $yaml);

        foreach ($this->getInitialServices() as $service => $class) {
            $this->container->register($service, $class);
        }

        return $this;
    }

    /**
     * Get a list of commands for the application.
     *
     * @return array
     */
    private function getCommands()
    {
        return [
            \Modrepo\Command\Install::class,
            \Modrepo\Command\Create::class,
            \Modrepo\Command\Modman::class,
            \Modrepo\Command\Composer::class,
            \Modrepo\Command\SelfUpdate::class
        ];
    }

    /**
     * Bootstrap the application with all commands.
     *
     * @return self
     */
    private function bootstrapCommands()
    {
        foreach ($this->getCommands() as $command) {
            $instance = new $command(null);

            if ($instance instanceof ContainerAwareInterface) {
                $instance->setContainer($this->container);
            }

            $this->add($instance);
        }

        return $this;
    }
}
