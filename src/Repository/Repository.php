<?php

namespace Modrepo\Repository;

/**
 * Responsibility: To model a repository in our system.
 *
 * @subpackage Modrepo\Repository
 * @package    Modrepo
 */
class Repository implements RepositoryInterface
{
    /** @var string */
    private $account;

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var bool */
    private $private;

    /** @var string */
    private $type;

    /** @var array */
    private $allowedTypes = ['git', 'hg'];

    /** @var string */
    private $service;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $url;

    /**
     * Repository constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->setAccount(isset($data['account']) ? $data['account'] : '');
        $this->setName(isset($data['name']) ? $data['name'] : '');
        $this->setDescription(isset($data['description']) ? $data['description'] : '');
        $this->setPrivate(isset($data['private']) ? $data['private'] : false);
        $this->setType(isset($data['type']) ? $data['type'] : 'git');
        $this->setUrl(isset($data['url']) ? $data['url'] : '');
        $this->setService(isset($data['service']) ? $data['service'] : '');
        $this->setUsername(isset($data['username']) ? $data['username'] : '');
        $this->setPassword(isset($data['password']) ? $data['password'] : '');
    }

    /**
     * Set the account name.
     *
     * @param  string $account
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setAccount($account)
    {
        if (!is_string($account)) {
            throw new \InvalidArgumentException('`account` is required and must be a string');
        }

        $this->account = $account;

        return $this;
    }

    /**
     * The account name.
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set the repository name.
     *
     * @param  string $name
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setName($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('`name` is required and must be a string.');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * The name of the repository.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the repository description.
     *
     * @param  string $description
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setDescription($description)
    {
        if (!is_string($description)) {
            throw new \InvalidArgumentException('`description must be a string`');
        }

        $this->description = $description;

        return $this;
    }

    /**
     * The description of the repository.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the repository access.
     *
     * @param  string $private
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setPrivate($private)
    {
        if (!is_bool($private)) {
            throw new \InvalidArgumentException(
                sprintf('`private` must be a boolean, %s provided', gettype($private))
            );
        }

        $this->private = $private;

        return $this;
    }

    /**
     * Is the repository public.
     *
     * @return bool
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set the type of repository. git, hg etc.
     *
     * @param  string $type
     * @return \Modrepo\Repository\Repository
     * @throws \InvalidArgumentException
     */
    public function setType($type)
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new \InvalidArgumentException(
                sprintf('Type %s is not allowed.', $type)
            );
        }

        $this->type = $type;
    }

    /**
     * The repository type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the repository hosting service.
     *
     * @param  string $service
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setService($service)
    {
        if (!is_string($service)) {
            throw new \InvalidArgumentException(
                sprintf('`service` must be a string, %s given.', gettype($service))
            );
        }

        $this->service = $service;

        return $this;
    }

    /**
     * Get the repository hosting service.
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set the repository username.
     *
     * @param  string $username
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setUsername($username)
    {
        if (!is_string($username)) {
            throw new \InvalidArgumentException(
                sprintf('`username` must be a string, %s given.', gettype($username))
            );
        }

        $this->username = $username;

        return $this;
    }

    /**
     * Get the repository username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the repository password.
     *
     * @param  string $password
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setPassword($password)
    {
        if (!is_string($password)) {
            throw new \InvalidArgumentException(
                sprintf('`password` must be a string, %s given.', gettype($password))
            );
        }

        $this->password = $password;

        return $this;
    }

    /**
     * Get the repository password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the repository URL.
     *
     * @param  string $url
     * @return $this
     */
    public function setUrl($url = null)
    {
        if (!is_null($url) && !is_string($url)) {
            throw new \InvalidArgumentException(
                sprintf('`url` must be a string, %s given.', gettype($url))
            );
        }

        $this->url = $url;

        return $this;
    }

    /**
     * Get the repository URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get the clone URL including the password.
     *
     * @return string
     * @throws \Modrepo\Repository\InvalidCloneUrlException
     */
    public function getCloneUrl()
    {
        if (empty($this->url)) {
            throw new InvalidCloneUrlException();
        }

        $search = sprintf('%s@', $this->username);
        $replace = sprintf('%s:%s@', urlencode($this->username), urlencode($this->password));

        return str_replace($search, $replace, $this->url);
    }
}
