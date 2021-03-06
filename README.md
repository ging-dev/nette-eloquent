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

use Illuminate\Database\Connection;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    /** @var Connection */
    protected $database;

    public function injectDatabase(Connection $database) {
        $this->database = $database;
    }

    public function actionDefault()
    {
        $this->database->getSchemaBuilder()->drop('users');
        $this->database->getSchemaBuilder()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name')->unique();
        });
        $this->database->table('users')->select('*')
            ->where('name', 'gingdev')
            ->get();
    }
}
```
