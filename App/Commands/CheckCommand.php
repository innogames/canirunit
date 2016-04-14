<?php

namespace App\Commands;

use App\Kernel;
use App\Message;
use App\MessageBag;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class CheckCommand extends Command
{

    /**
     * Configure the CheckCommand
     */
    protected function configure()
    {
        $this->setName('check')
            ->setDescription('Check if you can run your application');
    }

    /**
     * Execute the CheckCommand
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel = new Kernel();
        $result = $kernel->getResult();

        $output->writeln("<comment>Check your environment for the specified rules in the config:</comment>");
        $errors = $this->renderCheckTable($output, $result);

        if (count($errors) !== 0) {
            $output->writeln("\n<comment>The following problems were found with your environment:</comment>");
            $this->renderErrorsTable($output, $errors);
            $output->writeln("\n<error>Your environment is missing some dependencies!</error>");
        } else {
            $output->writeln("\n<info>Your environment is set!</info>");
        }
    }

    /**
     * @param OutputInterface $output
     * @param MessageBag[] $result
     * @return Message[]
     */
    private function renderCheckTable(OutputInterface $output, array $result)
    {
        $table = new Table($output);
        $table->setHeaders([
            'Title',
            'Required',
            'Status'
        ]);

        /** @var Message[] $errors */
        $errors = [];
        foreach ($result as $messageBag) {
            $status = '<info>Ok</info>';
            foreach ($messageBag->getMessages() as $message) {
                if ($message->getStatus()) {
                    continue;
                }

                $errors[] = "<info>{$messageBag->getTitle()}:</info> {$message->getMessage()}";
                $status = '<error>Error</error>';

            }

            $table->addRow([
                $messageBag->getTitle(),
                $this->formatRequired($messageBag->isRequired()),
                $status
            ]);
        }
        $table->render();

        return $errors;
    }

    /**
     * @param bool $required
     * @return string
     */
    private function formatRequired($required)
    {
        if ($required) {
            return '<info>Yes</info>';
        }

        return '<comment>No</comment>';
    }

    /**
     * @param OutputInterface $output
     * @param string[] $errors
     */
    private function renderErrorsTable(OutputInterface $output, array $errors)
    {
        $table = new Table($output);
        $table->setHeaders([
            'Message'
        ]);

        foreach ($errors as $error) {
            $table->addRow([$error]);
        }

        $table->render();
    }
}
