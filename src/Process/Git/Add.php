<?php

namespace Modrepo\Process\Git;

use Modrepo\Process\Process;
use Modrepo\Process\ProcessInterface;
use Symfony\Component\Process\Process as SymfonyProcess;

/**
 * Responsibility: To add the files in the working directory to the version control system.
 *
 * @subpackage Modrepo\Process
 * @package    Modrepo
 */
class Add extends Process implements ProcessInterface
{
    /**
     * Execute the process.
     *
     * @return Process
     */
    public function execute()
    {
        $process = new SymfonyProcess(
            sprintf('git add .')
        );

        $process->run();

        return $process;
    }
}
