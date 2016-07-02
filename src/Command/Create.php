<?php

namespace Modrepo\Command;

use Modrepo\Console\Command;
use Modrepo\Repository\Repository;
use Modrepo\Repository\RepositoryInterface;
use Modrepo\Process\Stack\Basic as BasicStack;
use Modrepo\Strategy\Factory as StrategyFactory;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Responsibility: To quickly upload Magento module code into a hosted repository.
 *
 * @subpackage Modrepo\Command
 * @package    Modrepo
 */
class Create extends Command
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('create')
            ->setDescription('Create a new module repository from the current working directory.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the repository, `module-name`' )
            ->addOption('description', 'd', InputArgument::OPTIONAL, 'A description for the repository', '')
            ->addOption('private', null, InputOption::VALUE_REQUIRED, 'Should the repository be private', true) // Private by default
            ->addOption('service', 's', InputOption::VALUE_REQUIRED, 'The service to use. Currently we only support `bitbucket`', 'bitbucket' )
            ->addOption('account', 'a', InputOption::VALUE_REQUIRED, 'The account name.')
            ->addOption('type', 't', InputOption::VALUE_REQUIRED, 'The type of repository to use. git,hg, etc.', 'git')
            ->addOption('username', 'u', InputOption::VALUE_REQUIRED, 'The username for the given service')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'The password for the given service');
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

        /** @var \Modrepo\Process\Stack\StackInterface $stack */
        $stack = new BasicStack();
        /** @var \Modrepo\Repository\RepositoryInterface $config */
        $repository = $this->prepareRepository($input, $config);
        /** @var \Modrepo\Strategy\StrategyInterface $strategy */
        $strategy = StrategyFactory::getInstance($stack, $repository);

        $this->outputInitialization($output, $repository);

        try {
            $strategy->execute();
        } catch (\Exception $e) {
            return $this->outputException($output, $e);
        }

        return $this->outputCompletion($output, $repository);
    }

    /**
     * Normalises the input for the command by merging input with configuration.
     *
     * @param  InputInterface $input
     * @param  ConfigurationInterface $config
     * @return RepositoryInterface
     */
    private function prepareRepository($input, $config)
    {
        return new Repository([
            'name' => $input->getArgument('name'),
            'description' => $this->getOption($input, 'description', ''),
            'private' => $this->getOption($input, 'private', true),
            'account' => $this->getOption($input, 'account', $config->get('account')),
            'type' => $this->getOption($input, 'type', $config->get('type')),
            'service' => $this->getOption($input, 'service', $config->get('service')),
            'username' => $this->getOption($input, 'username', $config->get('username')),
            'password' => $this->getOption($input, 'password', $config->get('password')),
        ]);
    }

    /**
     * Output the initialization message.
     *
     * @param  OutputInterface     $output
     * @param  RepositoryInterface $repository
     * @return void
     */
    private function outputInitialization(OutputInterface $output, RepositoryInterface $repository)
    {
        return $output->writeln(sprintf('Creating a %s %s repo called `%s/%s` on %s',
            $repository->getPrivate() ? 'private' : 'public',
            $repository->getType(),
            $repository->getAccount(),
            $repository->getName(),
            $repository->getService()
        ));
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
