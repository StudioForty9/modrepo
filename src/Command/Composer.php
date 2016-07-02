<?php

namespace Modrepo\Command;

use Modrepo\Console\Command;
use Modrepo\Process\Composer as ComposerProcess;
use Modrepo\Repository\Repository;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Responsibility: To quickly upload Magento module code into a hosted repository.
 *
 * @subpackage Modrepo\Command
 * @package    Modrepo
 */
class Composer extends Command
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('composer')
            ->setDescription('Create a new composer file.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the repository, `vendor/module`' )
            ->addOption('description', 'd', InputArgument::OPTIONAL, 'A description for the repository', '')
            ->addOption('private', null, InputOption::VALUE_REQUIRED, 'Should the repository be private', true);
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

        $repository = $this->prepareRepository($input, $config);
        $process = new ComposerProcess($repository);

        $output->writeln(
            $this->getJsonMessage($process->getComposerJson())
        );

        if (is_file($process->getComposerPath())) {
            $helper = $this->getHelper('question');
            $question = $this->getConfirmationQuesion();
            if (!$helper->ask($input, $output, $question)) {
                return $output->writeln("\n<error>Command aborted.</error>");
            }
        }

        try {
            $process->execute();
        } catch (\Exception $e) {
            return $this->outputException($output, $e);
        }

        return $this->outputCompletion($output);
    }

    /**
     * @return ConfirmationQuestion
     */
    private function getConfirmationQuesion()
    {
        return new ConfirmationQuestion(
            'Are you sure you want to create the composer.json file? <comment>[no]</comment> ', false
        );
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
        $repo = explode('/', $input->getArgument('name'));
        return new Repository([
            'name' => $repo[1],
            'description' => $this->getOption($input, 'description', ''),
            'private' => $this->getOption($input, 'private', true),
            'account' => isset($repo[0]) ? $repo[0] : $config->get('account'),
        ]);
    }

    /**
     * Output the initialization message.
     *
     * @param  OutputInterface $output
     * @return void
     */
    private function outputInitialization(OutputInterface $output)
    {
        return $output->writeln('Creating composer.json file...');
    }

    /**
     * Get the formatted JSON for the composer file.
     *
     * @return string
     */
    private function getJsonMessage($json)
    {
        $formatter = $this->getHelper('formatter');
        $intro = array('', $json, '');

        return $formatter->formatBlock($intro, 'comment');
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
