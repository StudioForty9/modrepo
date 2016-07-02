<?php

namespace Modrepo\Process\Git;

use Modrepo\Service\Factory;
use Modrepo\Process\Process;
use Modrepo\Process\ProcessInterface;
use Symfony\Component\Process\Process as SymfonyProcess;

/**
 * Responsibility: To download the repository from the hosted service using Git.
 *
 * @subpackage Modrepo\Process
 * @package    Modrepo
 */
class CloneHttps extends Process implements ProcessInterface
{
    /**
     * Execute the process.
     *
     * @return Process
     */
    public function execute()
    {
        $this->repository = $this->getService()->create($this->repository);

        $process = new SymfonyProcess(
            sprintf('git clone %s', $this->repository->getCloneUrl())
        );

        $process->run();

        return $process;
    }

    /**
     * Creates the service object to create the repository.
     *
     * @return ServiceInterface
     */
    private function getService()
    {
        return Factory::getInstance($this->repository->getService());
    }
}
