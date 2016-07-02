<?php

namespace Modrepo\Process\Git;

use Modrepo\Process\Process;
use Modrepo\Process\ProcessInterface;
use AndKirby\ModmanGenerator\Generator;
use Symfony\Component\Filesystem\Filesystem;

class Modman extends Process implements ProcessInterface
{
    /**
     * Execute the process.
     *
     * @return \Symfony\Component\Process\Process|null
     */
    public function execute()
    {
        $generator = new Generator();
        $list = $generator->generate();

        $filesystem = new Filesystem();
        $filesystem->dumpFile($this->getModmanPath(), $this->getOutput($list));
    }

    /**
     * Get the path to the modman file.
     *
     * @return string
     */
    private function getModmanPath()
    {
        return $this->getCurrentPath() . DIRECTORY_SEPARATOR . 'modman';
    }

    /**
     * Get the formatted output.
     *
     * @param  array $list
     * @return string
     */
    private function getOutput($list)
    {
        if (empty($list)) {
            return '';
        }

        $maxLength = max(array_map('strlen', $list));

        foreach ($list as $item) {
            if (is_file($item)) {
                $space = str_repeat(' ', $maxLength - strlen($item) + 3);
            } else {
                $item = rtrim($item, '/') . '/';
                $space = str_repeat(' ', $maxLength - strlen($item) + 2);
            }
            $output[] = "$item $space $item";
        }

        return implode("\n", $output) . "\n";
    }
}
