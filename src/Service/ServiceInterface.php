<?php

namespace Modrepo\Service;

use Modrepo\Repository\RepositoryInterface;

/**
 * Responsibility: To define the contract for creating a repository with a hosted service.
 *
 * @subpackage Modrepo\Service
 * @package    Modrepo
 */
interface ServiceInterface
{
    /**
     * Create a new repository.
     *
     * @param  RepositoryInterface $repo
     * @return mixed
     */
    public function create(RepositoryInterface $repo);
}
