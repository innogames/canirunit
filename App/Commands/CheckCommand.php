<?php

namespace App\Commands;

use App\Kernel;
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

        $output->writeln('<comment>Check your environment for the specified rules in the config:</comment>');
        $messages = $this->renderCheckTable($output, $result);

        if (count($messages) !== 0) {
            $output->writeln("\n<comment>Some dependencies are missing for you system (yellow are optional, red are required):</comment>");
            $this->renderMessagesTable($output, $messages);
        } else {
            $output->writeln('<info>YES, you can run your Application!</info>');
        }
    }

    /**
     * @param OutputInterface $output
     * @param MessageBag[] $result
     * @return string[]
     */
    private function renderCheckTable(OutputInterface $output, array $result)
    {
        $table = new Table($output);
        $table->setHeaders([
            'Title',
            'Required',
            'Status'
        ]);

        $messages = [];
        foreach ($result as $messageBag) {
            $status = '';

            foreach ($messageBag->getMessages() as $message) {
                $status = $this->formatStatus($messageBag->isRequired(), $message->getStatus());
                if ($message->getStatus()) {
                    continue;
                }

                if (!$messageBag->isRequired()) {
                    $messages[] = "<info>{$messageBag->getTitle()}:</info> <comment>{$message->getMessage()}</comment>";
                    continue;
                }

                $messages[] = "<info>{$messageBag->getTitle()}:</info> <error>{$message->getMessage()}</error>";
            }

            $table->addRow([
                $messageBag->getTitle(),
                $this->formatRequired($messageBag->isRequired()),
                $status
            ]);
        }
        $table->render();

        return $messages;
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
     * @param bool $required
     * @param bool $status
     * @return string
     */
    private function formatStatus($required, $status)
    {
        if ($status) {
            return '<info>Installed</info>';
        }

        if (!$status && $required) {
            return '<error>Missing [required]</error>';
        }

        return '<comment>Missing [optional]</comment>';
    }

    /**
     * @param OutputInterface $output
     * @param string[] $messages
     */
    private function renderMessagesTable(OutputInterface $output, array $messages)
    {
        $table = new Table($output);
        $table->setHeaders([
            'Message',
        ]);

        foreach ($messages as $message) {
            $table->addRow([$message]);
        }

        $table->render();
    }
}
