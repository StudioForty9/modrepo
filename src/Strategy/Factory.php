<?php

namespace Modrepo\Strategy;

use Modrepo\Process\Stack\StackInterface;
use Modrepo\Repository\RepositoryInterface;
use Modrepo\Exception\StrategyNotFoundException;

/**
 * Responsibility: To create a new strategy object.
 *
 * @subpackage Modrepo\Strategy
 * @package    Modrepo
 */
class Factory
{
    const CLASS_INTERFACE = '\Modrepo\Strategy\StrategyInterface';

    /** @var array  */
    private $strategies = [
        'git' => \Modrepo\Strategy\Git::class
    ];

    /**
     * Create a new strategy instance.
     *
     * @param  StackInterface      $stack
     * @param  RepositoryInterface $repository
     * @return StrategyInterface
     * @throws StrategyNotFoundException
     */
    public function newInstance(StackInterface $stack, RepositoryInterface $repository)
    {
        $strategy = $repository->getType();

        if (!array_key_exists($strategy, $this->strategies)) {
            throw new StrategyNotFoundException(
                sprintf('Unable to find a strategy for `%s`', $strategy)
            );
        }

        $class = $this->strategies[$strategy];

        if (!$this->isClassImplementingInterface($class, self::CLASS_INTERFACE)) {
            throw new \RuntimeException(
                sprintf('%s does not implement %s', $class, self::CLASS_INTERFACE)
            );
        }

        return new $class($stack, $repository);
    }

    /**
     * Shortcut to create a new strategy instance.
     *
     * @param  StackInterface      $stack
     * @param  RepositoryInterface $repository
     * @return StrategyInterface
     * @throws NotFoundException
     */
    public static function getInstance(StackInterface $stack, RepositoryInterface $repository)
    {
        //$object = new static();

        return (new static())->newInstance($stack, $repository);
    }

    /**
     * Determine if a class implements an interface
     *
     * @param  string $class
     * @param  string $interface
     * @return bool
     */
    private function isClassImplementingInterface($class, $interface)
    {
        $classes = class_implements($class);

        return isset($classes[ltrim($interface, '\\')]);
    }
}
