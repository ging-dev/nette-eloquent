<?php

declare(strict_types=1);

namespace Gingdev\NetteExtension;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Output\OutputInterface;

class Migration
{
    protected Migrator $migrator;

    public function __construct()
    {
        $connection = Manager::connection();

        $resolver = new ConnectionResolver(['default' => $connection]);
        $resolver->setDefaultConnection('default');

        $repository = new DatabaseMigrationRepository($resolver, 'migrations');

        if (!$repository->repositoryExists()) {
            $repository->createRepository();
        }

        $this->migrator = new Migrator($repository, $resolver, new Filesystem());
    }

    protected function getMigrationPath()
    {
        return getcwd().DIRECTORY_SEPARATOR.'migrations';
    }

    public function create(string $name, ?string $table, bool $create = false)
    {
        $creator = new MigrationCreator(new Filesystem(), __DIR__.DIRECTORY_SEPARATOR.'stub');

        $path = $creator->create($name, $this->getMigrationPath(), $table, $create);

        return pathinfo($path, PATHINFO_FILENAME);
    }

    public function setMigratorOutput(OutputInterface $output): void
    {
        $this->migrator->setOutput($output);
    }

    public function run(array $options = []): array
    {
        return $this->migrator->run($this->getMigrationPath(), $options);
    }

    public function rollback(array $options = []): array
    {
        return $this->migrator->rollback($this->getMigrationPath(), $options);
    }
}
