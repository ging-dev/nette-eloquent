<?php

declare(strict_types=1);

namespace Gingdev\NetteExtension;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;
use Nette\Utils\Helpers;
use Tracy\Debugger;
use Tracy\IBarPanel;

class EloquentPanel implements IBarPanel
{
    private $totalTime = 0;

    private $queries = [];

    public function register(Manager $connection)
    {
        Debugger::getBar()->addPanel($this);
        $connection->connection()->setEventDispatcher(new Dispatcher());
        $connection->connection()->listen(function ($query) {
            $this->queries[] = $query;
            $this->totalTime += $query->time;
        });
    }

    public function getTab()
    {
        return Helpers::capture(function () {
            $count = count($this->queries);
            $totalTime = $this->totalTime;
            require __DIR__.'/templates/tab.phtml';
        });
    }

    public function getPanel()
    {
        return Helpers::capture(function () {
            $queries = $this->queries;
            require __DIR__.'/templates/panel.phtml';
        });
    }
}
