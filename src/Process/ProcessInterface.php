<?php

namespace Modrepo\Process;

/**
 * Responsibility: To define the contract for a process.
 *
 * @subpackage Modrepo\Process
 * @package    Modrepo
 */
interface ProcessInterface
{
    /**
     * Execute the process.
     *
     * @return \Symfony\Component\Process\Process|null
     */
    public function execute();
}
