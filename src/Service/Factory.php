<?php

namespace Modrepo\Service;

use Modrepo\Exception\ServiceNotFoundException;

/**
 * Responsibility: To create a service object.
 *
 * @subpackage Modrepo\Service
 * @package    Modrepo
 */
class Factory
{
    /**
     * @var array
     */
    private $types = [
        'bitbucket' => \Modrepo\Service\Bitbucket::class
    ];

    /**
     * Create a new service instance.
     *
     * @param  string $type
     * @return \Modrepo\Service\ServiceInterface
     * @throws \Modrepo\Exception\ServiceNotFoundException
     */
    public function newInstance($type)
    {
        if (array_key_exists($type, $this->types)) {
            $class = $this->types[$type];
            return new $class();
        }

        throw new ServiceNotFoundException(
            sprintf('Unable to find a service of type `%s`', $type)
        );
    }

    /**
     * Create a new service instance.
     *
     * @param  string $type
     * @return \Modrepo\Service\ServiceInterface
     * @throws \Modrepo\Service\NotFoundException
     */
    public static function getInstance($type)
    {
        $object = new static();

        return $object->newInstance($type);
    }
}
