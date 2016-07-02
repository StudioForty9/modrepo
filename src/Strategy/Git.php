<?php

namespace Modrepo\Strategy;

use Modrepo\Process\Move;
use Modrepo\Process\Clean;
use Modrepo\Process\Composer;
use Modrepo\Process\Git\Add;
use Modrepo\Process\Git\CloneHttps;
use Modrepo\Process\Git\Modman;
use Modrepo\Process\Git\Upload;
use Modrepo\Process\Stack\StackInterface;
use Modrepo\Repository\RepositoryInterface;

/**
 * Responsibility: To execute the Git strategy.
 *
 * @subpackage Modrepo\Strategy
 * @package    Modrepo
 */
class Git implements StrategyInterface
{
    /** @var \Modrepo\Process\StackInterface */
    private $stack;

    /** @var \Modrepo\Repository\RepositoryInterface */
    private $repository;

    /**
     * Git Strategy constructor.
     *
     * @param StackInterface      $stack
     * @param RepositoryInterface $repository
     */
    public function __construct(StackInterface $stack, RepositoryInterface $repository)
    {
        $this->stack = $stack;
        $this->repository = $repository;
    }

    /**
     * Run through the chain.
     *
     * @return void
     */
    public function execute()
    {
        $this->stack->add(new Clean($this->repository));
        $this->stack->add(new CloneHttps($this->repository));
        $this->stack->add(new Move($this->repository));
        $this->stack->add(new Add($this->repository));
        $this->stack->add(new Modman($this->repository));
        $this->stack->add(new Composer($this->repository));
        $this->stack->add(new Add($this->repository));
        $this->stack->add(new Upload($this->repository));
        $this->stack->add(new Clean($this->repository));

        $this->stack->run();
    }
}
