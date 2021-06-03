<?php

declare(strict_types=1);

namespace Gingdev\NetteExtension;

use Illuminate\Database\Connection;
use Illuminate\Events\Dispatcher;
use Nette\Utils\Helpers;
use Tracy\Debugger;
use Tracy\IBarPanel;

class EloquentPanel implements IBarPanel
{
    private $connectionName;

    private $totalTime = 0;

    private $queries = [];

    public function register(Connection $connection, string $name)
    {
        Debugger::getBar()->addPanel($this);
        $connection->setEventDispatcher(new Dispatcher());
        $connection->listen(function ($query) {
            $this->queries[] = $query;
            $this->totalTime += $query->time;
        });
        $this->connectionName = $name;
    }

    public function getTab()
    {
        return Helpers::capture(function () {
            $connectionName = $this->connectionName;
            $count = count($this->queries);
            $totalTime = $this->totalTime;
            require __DIR__.'/templates/tab.phtml';
        });
    }

    public function getPanel()
    {
        return Helpers::capture(function () {
            if (!$queries = $this->queries) {
                return;
            }
            $connectionName = $this->connectionName;
            $totalTime = $this->totalTime;
            require __DIR__.'/templates/panel.phtml';
        });
    }
}
