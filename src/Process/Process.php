<?php

namespace Modrepo\Process;

use Modrepo\Repository\RepositoryInterface;

/**
 * Responsibility: To share common functionality between processes.
 *
 * @subpackage Modrepo\Process
 * @package    Modrepo
 */
abstract class Process
{
    /** @var string */
    protected $cwd;

    /** @var \Modrepo\Repository\RepositoryInterface */
    protected $repository;

    /**
     * Clone a git repository.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository = null)
    {
        $this->repository = $repository;
    }

    /**
     * Get the path to the current working directory.
     *
     * @return string
     */
    protected function getCurrentPath()
    {
        if (is_null($this->cwd)) {
            $this->cwd = getcwd();
        }

        return $this->cwd;
    }

    /**
     * Get the path to the vcs in the current directory.
     *
     * @return bool
     */
    protected function getCurrentVcsPath()
    {
        return $this->getCurrentPath() . DIRECTORY_SEPARATOR . '.' . $this->repository->getType();
    }

    /**
     * Get the path to the cloned repository.
     *
     * @return string
     */
    protected function getClonePath()
    {
        return $this->getCurrentPath() . DIRECTORY_SEPARATOR . $this->repository->getName();
    }

    /**
     * Get the path to the cloned vcs directory.
     *
     * @return string
     */
    protected function getClonedVcsPath()
    {
        return $this->getClonePath() . DIRECTORY_SEPARATOR . '.' . $this->repository->getType();
    }
}
