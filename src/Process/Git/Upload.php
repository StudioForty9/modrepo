<?php

namespace Modrepo\Process\Git;

use Modrepo\Process\Process;
use Modrepo\Process\ProcessInterface;
use Symfony\Component\Process\Process as SymfonyProcess;

/**
 * Responsibility: To upload the files in the working directory to the hosted service.
 *
 * @subpackage Modrepo\Process
 * @package    Modrepo
 */
class Upload extends Process implements ProcessInterface
{
    /**
     * Execute the process.
     *
     * @return Process
     */
    public function execute()
    {
        $process = new SymfonyProcess(
            sprintf('git commit -m "Initial commit" && git push')
        );

        $process->run();

        return $process;
    }
}
