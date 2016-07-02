<?php

namespace Modrepo\Repository;

/**
 * Responsibility: To define the contract for a repository.
 *
 * @subpackage Modrepo\Repository
 * @package    Modrepo
 */
interface RepositoryInterface
{
    /**
     * The account name.
     *
     * @return string
     */
    public function getAccount();

    /**
     * The name of the repository.
     *
     * @return string
     */
    public function getName();

    /**
     * The description of the repository.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Is the repository private or public.
     *
     * @return bool
     */
    public function getPrivate();

    /**
     * The repository type.
     *
     * @return string
     */
    public function getType();

    /**
     * The description of the repository.
     *
     * @return string
     */
    public function getService();

    /**
     * Is the repository private or public.
     *
     * @return bool
     */
    public function getUsername();

    /**
     * The repository type.
     *
     * @return string
     */
    public function getPassword();

    /**
     * Set the repository URL.
     *
     * @param  string $url
     * @return $this
     */
    public function setUrl($url);

    /**
     * Get the repository URL.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Get the repository clone URL.
     *
     * @return string
     */
    public function getCloneUrl();
}
