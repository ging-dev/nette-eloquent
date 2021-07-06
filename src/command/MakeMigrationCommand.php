<?php

declare(strict_types=1);

namespace Gingdev\NetteExtension\Command;

use Exception;
use Gingdev\NetteExtension\Migration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeMigrationCommand extends Command
{
    protected static $defaultName = 'make:migration';

    protected function configure(): void
    {
        $this->setDescription('Creates a new migration')
            ->setHelp('This command allows you to create a migration.')
            ->addArgument('name', InputArgument::REQUIRED, 'Migration name.')
            ->addOption('table', 't', InputOption::VALUE_OPTIONAL, 'The name of the table that will be specified in the migration code.')
            ->addOption('create', null, InputOption::VALUE_NONE, 'Migration to create a table.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        try {
            $migration = new Migration();
            $migration_name = $migration->create(
                $input->getArgument('name'),
                $input->getOption('table'),
                $input->getOption('create'),
            );

            $style->success('Migration was created: '.$migration_name);
        } catch (Exception $exception) {
            $style->error($exception->getMessage());
        }

        return Command::SUCCESS;
    }
}
