<?php

namespace Modrepo\Command;

use Humbug\SelfUpdate\Updater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Responsibility: To update to local phar file with the latest phar file.
 *
 * @subpackage Modrepo\Command
 * @package    Modrepo
 */
class SelfUpdate extends Command
{
    /**
     * Execute the self-update command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $updater = new Updater(null, false);
        $updater->setStrategy(Updater::STRATEGY_GITHUB);
        $updater->getStrategy()->setPackageName('studioforty9/modrepo');
        $updater->getStrategy()->setPharName('modrepo.phar');
        $updater->getStrategy()->setCurrentLocalVersion($this->getApplication()->getVersion());
        $updater->getStrategy()->setStability('stable');

        try {
            $result = $updater->update();
            if ($result) {
                $output->writeln('<fg=green>Modrepo has been updated.</fg=green>');
                $output->writeln(sprintf(
                    '<fg=green>Current version is:</fg=green> <options=bold>%s</options=bold>.',
                    $updater->getNewVersion()
                ));
                $output->writeln(sprintf(
                    '<fg=green>Previous version was:</fg=green> <options=bold>%s</options=bold>.',
                    $updater->getOldVersion()
                ));
            } else {
                $output->writeln('<fg=green>Modrepo is currently up to date.</fg=green>');
                $output->writeln(sprintf(
                    '<fg=green>Current version is:</fg=green> <options=bold>%s</options=bold>.',
                    $updater->getOldVersion()
                ));
            }
        } catch (\Exception $e) {
            $output->writeln(sprintf('Error: <fg=yellow>%s</fg=yellow>', $e->getMessage()));
        }
        $output->write(PHP_EOL);
    }

    /**
     * Configure the command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('self-update')
            ->setDescription('Update modrepo.phar to most the latest version.');
    }
}
