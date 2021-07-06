<?php

declare(strict_types=1);

namespace Gingdev\NetteExtension\Command;

use Gingdev\NetteExtension\Migration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    protected static $defaultName = 'migrate';

    protected function configure(): void
    {
        $this->setDescription('Run or rollback migrations')
            ->setHelp('The command allows you to run and rollback migrations.')
            ->addOption('pretend', null, InputOption::VALUE_NONE, 'Show SQL queries without performing migration.')
            ->addOption('step', null, InputOption::VALUE_OPTIONAL, 'Force the migrations to be run so they can be rolled back individually. The number of migrations to be reverted when running rollback command.')
            ->addOption('rollback', null, InputOption::VALUE_NONE, 'Rollback.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $migrations = new Migration();
        $migrations->setMigratorOutput($output);

        $options = [
            'pretend' => $input->getOption('pretend'),
            'step' => $input->getOption('step'),
        ];

        if ($input->getOption('rollback')) {
            $migrations->rollback($options);

            return Command::SUCCESS;
        }

        $migrations->run($options);

        return Command::SUCCESS;
    }
}
