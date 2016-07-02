<?php

namespace Modrepo\Command;

use Exception;
use Modrepo\Console\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Responsibility: To install the configuration file for the application.
 *
 * @subpackage Modrepo\Command
 * @package    Modrepo
 */
class Install extends Command
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('install')
            ->setDescription('Install the configuration file in the user directory');
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @throws Exception
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Send the introduction message
        $output->writeln($this->getIntroOutput());

        // Build up the questions
        $questions = [
            'service'  => $this->getServiceQuestion(),
            'account'  => $this->getAccountQuestion(),
            'type'     => $this->getVcsTypeQuestion(),
            'username' => $this->getUsernameQuestion(),
            'password' => $this->getPasswordQuestion(),
        ];

        // Ask the questions
        $question = $this->getHelper('question');
        $answers = [
            'service'  => $question->ask($input, $output, $questions['service']),
            'account'  => $question->ask($input, $output, $questions['account']),
            'type'     => $question->ask($input, $output, $questions['type']),
            'username' => $question->ask($input, $output, $questions['username']),
            'password' => $question->ask($input, $output, $questions['password']),
        ];

        $this->validateAnswers($output, $answers);

        $config = $this->getContainer()->get('config');

        try {
            $data = array_merge($this->getDefaultConfiguration(), $answers);
            $config->setConfig($data)->save();
        } catch (Exception $e) {
            $output->writeln($this->getErrorMessage($config->getFilepath(), $e));
            exit(1);
        }

        $output->writeln($this->getSuccessMessage($config->getFilepath()));
    }

    /**
     * Get the service question.
     *
     * @return \Symfony\Component\Console\Question\ChoiceQuestion
     */
    private function getServiceQuestion()
    {
        return new ChoiceQuestion(
            'Please select a service', [1 => 'bitbucket'], 'bitbucket'
        );
    }

    /**
     * Get the vcs type question.
     *
     * @return \Symfony\Component\Console\Question\ChoiceQuestion
     */
    private function getVcsTypeQuestion()
    {
        return new ChoiceQuestion(
            'Please enter your vcs type: ', [1 => 'git'], 'git'
        );
    }

    /**
     * Get the account question.
     *
     * @return \Symfony\Component\Console\Question\Question
     */
    private function getAccountQuestion()
    {
        return new Question('Please enter your account name: ');
    }

    /**
     * Get the username question.
     *
     * @return \Symfony\Component\Console\Question\Question
     */
    private function getUsernameQuestion()
    {
        return new Question('Please enter your username: ');
    }

    /**
     * Get the password question.
     *
     * @return \Symfony\Component\Console\Question\Question
     */
    private function getPasswordQuestion()
    {
        $question = new Question('Please enter your password: ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);

        return $question;
    }

    /**
     * Get the default configuration.
     *
     * @return array
     */
    private function getDefaultConfiguration()
    {
        return [
            'service' => 'bitbucket',
            'account' => '',
            'type' => 'git',
            'username' => '',
            'password' => '',
        ];
    }

    /**
     * Get the formatted introduction message.
     *
     * @return string
     */
    private function getIntroOutput()
    {
        $formatter = $this->getHelper('formatter');
        $intro = array('', $this->getApplication()->getName() . ' Installation', '');

        return $formatter->formatBlock($intro, 'comment');
    }

    /**
     * Get the formatted validation error messages.
     *
     * @param $field
     * @return mixed
     */
    private function getValidationErrorMessage($field)
    {
        $formatter = $this->getHelper('formatter');
        $intro = array('', sprintf('You must provide a value for %s.', $field), '');

        return $formatter->formatBlock($intro, 'error');

    }

    /**
     * Get the formatted success message for installing configuration.
     *
     * @param  string $configPath
     * @return string
     */
    private function getSuccessMessage($configPath)
    {
        $formatter = $this->getHelper('formatter');
        $intro = array(
            '',
            sprintf('Configuration installed successfully at %s.', $configPath),
            ''
        );

        return $formatter->formatBlock($intro, 'info');
    }

    /**
     * Get the formatted error message for installing configuration.
     *
     * @param string    $configPath
     * @param Exception $exception
     * @return string
     */
    private function getErrorMessage($configPath, Exception $exception)
    {
        $formatter = $this->getHelper('formatter');
        $intro = array(
            '',
            sprintf('Configuration could not be installed successfully at %s.', $configPath),
            $exception->getMessage(),
            ''
        );

        return $formatter->formatBlock($intro, 'error');
    }

    /**
     * Validate the answers. Basic presence checking :)
     *
     * @param  OutputInterface $output
     * @param  array           $answers
     * @return void
     */
    private function validateAnswers($output, $answers)
    {
        foreach ($answers as $context => $answer) {
            if (empty($answer)) {
                $output->writeln($this->getValidationErrorMessage($context));
                exit(1);
            }
        }
    }
}
