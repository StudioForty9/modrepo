<?php

namespace Modrepo\Process;

use Symfony\Component\Process\Process as SymfonyProcess;

/**
 * Responsibility: To move the repository tracking files from the cloned directory to the
 * working directory.
 *
 * @subpackage Modrepo\Process
 * @package    Modrepo
 */
class Move extends Process implements ProcessInterface
{
    /**
     * Execute the process.
     *
     * @return void
     */
    public function execute()
    {
        $clonedVcs = $this->getClonedVcsPath();
        $currentVcs = $this->getCurrentVcsPath();

        $process = new SymfonyProcess(
            sprintf('mv %s %s', $this->escape($clonedVcs), $this->escape($currentVcs))
        );

        $process->run();

        return $process;
    }

    /**
     * Escape special characters
     *
     * @param  string $path
     * @return string
     */
    private function escape($path)
    {
        return str_replace(" ", "\\ ", escapeshellarg($path));
    }
}
