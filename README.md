# Nette-eloquent
Eloquent Bridge for Nette Framework

[![StyleCI](https://github.styleci.io/repos/372696270/shield?branch=main)](https://github.styleci.io/repos/372696270?branch=main)


## Installation
```sh
composer require ging-dev/nette-eloquent
```

## Configuration
```neon
extensions:
    eloquent: Gingdev\NetteExtension\EloquentExtension


eloquent:
    driver: sqlite
    database: %appDir%/database.db
```

## Example
```php
<?php

declare(strict_types=1);

namespace App\Presenters;

use Illuminate\Database\Capsule\Manager;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    /** @var Manager */
    protected $database;

    public function injectDatabase(Manager $database) {
        $this->database = $database;
    }

    public function actionDefault()
    {
        $this->database->schema()->drop('users');
        $this->database->schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name')->unique();
        });
        $this->database->table('users')->select('*')
            ->where('name', 'gingdev')
            ->get();
    }
}
```
