<?php

namespace Modrepo\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Responsibility: To allow commands to share common functionality.
 *
 * @subpackage Modrepo\Command
 * @package    Modrepo
 */
class Command extends SymfonyCommand implements ContainerAwareInterface
{
    /** @var ContainerInterface  */
    protected $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Gets the container.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get an argument from input or fallback to a default.
     *
     * @param InputInterface $input
     * @param string         $name
     * @param mixed          $default
     *
     * @return mixed
     */
    public function getArgument(InputInterface $input, $name, $default = null)
    {
        $value = $input->getArgument($name);

        if (empty($value)) {
            return $default;
        }

        return $value;
    }

    /**
     * Get an option from input or fallback to a default.
     *
     * @param InputInterface $input
     * @param string         $name
     * @param mixed          $default
     *
     * @return mixed
     */
    public function getOption(InputInterface $input, $name, $default = null)
    {
        $value = $input->getOption($name);
        if (empty($value)) {
            return $default;
        }

        return $value;
    }
}
