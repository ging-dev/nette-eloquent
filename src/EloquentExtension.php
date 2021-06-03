<?php

declare(strict_types=1);

namespace Gingdev\NetteExtension;

use Illuminate\Database\Capsule\Manager;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

class EloquentExtension extends CompilerExtension
{
    public function getConfigSchema(): Schema
    {
        /** @var Schema */
        $schema = Expect::arrayOf(
            Expect::array()
        )->before(fn ($value) => is_array(reset($value)) || null === reset($value)
            ? $value
            : ['default' => $value]
        );

        return $schema;
    }

    public function loadConfiguration()
    {
        $container = $this->getContainerBuilder();

        $manager = $container->addDefinition($this->prefix('manager'))
            ->setFactory(Manager::class)
            ->addSetup('setAsGlobal')
            ->addSetup('bootEloquent')
            ->setAutowired(true);

        $autowired = true;
        foreach ($this->config as $name => $config) {
            $config['autowired'] ??= $autowired;

            $manager->addSetup('addConnection', [$config, $name]);

            $connection = $container->addDefinition($this->prefix("$name.connection"))
                ->setFactory([$manager, 'connection'], [$name])
                ->setAutowired($config['autowired']);

            $autowired = false;

            if (!$container->parameters['debugMode']) {
                continue;
            }

            $panel = $container->addDefinition($this->prefix("$name.panel"))
                ->setFactory(EloquentPanel::class)
                ->setAutowired(false);

            $connection->addSetup([$panel, 'register'], [$connection, $name]);
        }
    }
}
