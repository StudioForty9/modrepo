<?php

namespace Modrepo\Process\Stack;

use Modrepo\Process\ProcessInterface;

/**
 * Responsibility: To define the contract for a process stack.
 *
 * @subpackage Modrepo\Process
 * @package    Modrepo
 */
interface StackInterface
{
    /**
     * Add a process to the stack.
     *
     * @param  ProcessInterface $process
     * @return self
     */
    public function add(ProcessInterface $process);

    /**
     * Run all processes in the stack.
     *
     * @return void
     */
    public function run();
}
