<?php

namespace Modrepo\Strategy;

/**
 * Responsibility: To define a contract for executing a strategy.
 *
 * @subpackage Modrepo\Strategy
 * @package    Modrepo
 */
interface StrategyInterface
{
    /**
     * Execute the strategy.
     *
     * @return void
     */
    public function execute();
}
