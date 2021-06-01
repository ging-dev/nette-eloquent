<?php

declare(strict_types=1);

namespace Gingdev\NetteExtension;

use Illuminate\Database\Capsule\Manager;
use Nette\DI\CompilerExtension;

class EloquentExtension extends CompilerExtension
{
    public function loadConfiguration()
    {
        $container = $this->getContainerBuilder();
        $config = $this->getConfig();

        $connection = $container->addDefinition($this->prefix('connection'))
            ->setFactory(Manager::class)
            ->addSetup('addConnection', [$config])
            ->addSetup('setAsGlobal')
            ->addSetup('bootEloquent')
            ->setAutowired(true);

        if ($container->parameters['debugMode']) {
            $panel = $container->addDefinition($this->prefix('panel'))
                ->setFactory(EloquentPanel::class);
            $connection->addSetup([$panel, 'register'], [$connection]);
        }
    }
}
