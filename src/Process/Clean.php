<?php

namespace Modrepo\Process;

use Symfony\Component\Process\Process as SymfonyProcess;

/**
 * Responsibility: To clean the working directory so it does not contain the cloned
 * repository directory.
 *
 * @subpackage Modrepo\Process
 * @package    Modrepo
 */
class Clean extends Process implements ProcessInterface
{
    /**
     * Execute the process.
     *
     * @return Process
     */
    public function execute()
    {
        $process = new SymfonyProcess(
            sprintf('rm -rf %s', $this->getClonePath())
        );

        $process->run();

        return $process;
    }
}
