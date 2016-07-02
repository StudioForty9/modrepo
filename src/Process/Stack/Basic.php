<?php

namespace Modrepo\Process\Stack;

use Modrepo\Process\ProcessInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Responsibility: To allow for a basic implementation of a process stack.
 *
 * @subpackage Modrepo\Process
 * @package    Modrepo
 */
class Basic implements StackInterface
{
    /** @var array */
    private $stack = [];

    /**
     * Add a process to the stack.
     *
     * @param  ProcessInterface $process
     * @return self
     */
    public function add(ProcessInterface $process)
    {
        $this->stack[] = $process;

        return $this;
    }

    /**
     * Run all processes in the stack.
     *
     * @return void
     * @throws RuntimeException
     * @throws ProcessFailedException
     */
    public function run()
    {
        if (empty($this->stack)) {
            throw new \RuntimeException('Unable to run, no processes in the stack.');
        }

        foreach ($this->stack as $link) {
            $process = $link->execute();
            if ($process instanceof Process && !$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        }
    }
}
