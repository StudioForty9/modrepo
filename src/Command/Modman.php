<?php

namespace Modrepo\Command;

use Modrepo\Console\Command;
use Modrepo\Process\Git\ModmanProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Responsibility: To quickly upload Magento module code into a hosted repository.
 *
 * @subpackage Modrepo\Command
 * @package    Modrepo
 */
class Modman extends Command
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('modman')
            ->setDescription('Create a new modman file from the files in the current working directory.');
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Modrepo\Configuration\ConfigurationInterface $config */
        $config = $this->getContainer()->get('config');
        if (! $config->isLoaded()) {
            $config->load();
        }

        $this->outputInitialization($output);

        try {
            $process = new \Modrepo\Process\Git\Modman();
            $process->execute();
        } catch (\Exception $e) {
            return $this->outputException($output, $e);
        }

        return $this->outputCompletion($output);
    }

    /**
     * Output the initialization message.
     *
     * @param  OutputInterface $output
     * @return void
     */
    private function outputInitialization(OutputInterface $output)
    {
        return $output->writeln('Creating modman file...');
    }

    /**
     * Output completion message.
     *
     * @param  OutputInterface $output
     * @return void
     */
    private function outputCompletion(OutputInterface $output)
    {
        return $output->writeln('Done');
    }

    /**
     * Output an exception message.
     *
     * @param  OutputInterface $output
     * @param  \Exception      $exception
     * @return void
     */
    private function outputException(OutputInterface $output, \Exception $exception)
    {
        return $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
    }
}
