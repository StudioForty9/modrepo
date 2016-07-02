<?php

namespace Modrepo\Process;

use Symfony\Component\Filesystem\Filesystem;

class Composer extends Process implements ProcessInterface
{
    /**
     * Execute the process.
     *
     * @return \Symfony\Component\Process\Process|null
     */
    public function execute()
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->getComposerPath(), $this->getComposerJson());
    }

    /**
     * Get the composer path.
     *
     * @return string
     */
    public function getComposerPath()
    {
        return $this->getCurrentPath() . DIRECTORY_SEPARATOR . 'composer.json';
    }

    /**
     * Get the composer json.
     *
     * @return string
     */
    public function getComposerJson()
    {
        return json_encode($this->getComposerStub(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get the composer json stub.
     *
     * @return array
     */
    private function getComposerStub()
    {
        return [
            "name" => sprintf('%s/%s', $this->repository->getAccount(), $this->repository->getName()),
            "description" => $this->repository->getDescription(),
            "type" => "magento-module",
            "version" => "dev-master",
            "require" => [
                "magento-hackathon/magento-composer-installer" => "*"
            ]
        ];
    }
}
