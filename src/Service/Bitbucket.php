<?php

namespace Modrepo\Service;

use Bitbucket\API\Authentication\Basic;
use Bitbucket\API\Repositories\Repository;
use Modrepo\Repository\RepositoryInterface;
use Modrepo\Exception\JsonParsingException;
use Modrepo\Exception\ServiceResponseException;
use Modrepo\Exception\ServiceAuthenticationException;

/**
 * Responsibility: To create a Bitbucket service object.
 *
 * @subpackage Modrepo\Service
 * @package    Modrepo
 */
class Bitbucket implements ServiceInterface
{
    /**
     * Create a new repository.
     *
     * @param  RepositoryInterface $repo
     * @return RepositoryInterface
     * @throws ServiceAuthenticationException
     * @throws \Exception
     */
    public function create(RepositoryInterface $repo)
    {
        try {
            $api = $this->bootstrapApi($repo);
        } catch (\Exception $e) {
            throw new ServiceAuthenticationException($e->getMessage());
        }

        $response = $api->create($repo->getAccount(), $repo->getName(), array(
            'scm'         => $repo->getType(),
            'description' => $repo->getDescription(),
            'language'    => 'php',
            'is_private'  => $repo->getPrivate(),
            'fork_policy' => 'no_public_forks',
        ));

        $content = $response->getContent();
        $this->guardAgainstMissingContent($content);
        $repository = $this->parseJson($content);
        $this->guardAgainstJsonParsingError();
        $this->guardAgainstServiceError($repository);

        $repo->setUrl($repository['links']['clone'][0]['href']);

        return $repo;
    }

    /**
     * Bootstrap the Bitbucket API for Repositories.
     *
     * @param  RepositoryInterface $repo
     * @return Repository
     */
    private function bootstrapApi(RepositoryInterface $repo)
    {
        $api = new Repository();
        $credentials = new Basic(
            $repo->getUsername(), $repo->getPassword()
        );
        $api->setCredentials($credentials);

        return $api;
    }

    /**
     * Ensure the response has content.
     *
     * @param  string $response
     * @throws ServiceResponseException
     */
    private function guardAgainstMissingContent($response)
    {
        if ('' === $response) {
            throw new ServiceResponseException(
                'Please check your Bitbucket credentials and ensure the repo does not already exist.'
            );
        }
    }

    /**
     * Parse the JSON response.
     *
     * @param  string $response
     * @return string
     */
    private function parseJson($response)
    {
        return @json_decode($response, true);
    }

    /**
     * Ensure the JSON response is valid.
     *
     * @throws JsonParsingException
     */
    private function guardAgainstJsonParsingError()
    {
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonParsingException(json_last_error_msg());
        }
    }

    /**
     * Ensure the service response does not contain an error message.
     *
     * @param  array $json
     * @throws ServiceResponseException
     */
    private function guardAgainstServiceError($json)
    {
        if (isset($json['error']['message'])) {
            throw new ServiceResponseException($json['error']['message']);
        }
    }
}
