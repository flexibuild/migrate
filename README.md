Yii2 migrate extension
======================
Helpful tools for yii2 migrations.


With this extenension you can:

- forget about wrapping table names like `{{%table_name}}`, extension will do it automatically.
- create indexes and foreign keys without inventing them names.
- create table without passing character set and collate. The extension will do it by default.
- a simple table creation with exntending from `flexibuild\migrate\db\CreateTableMigration`.
- and other small features.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist flexibuild/migrate "*"
```

or add

```
"flexibuild/migrate": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by configuring your console application:

```php
return [
    ...
    'controllerMap' => [
        ...
        'migrate' => [
            'class' => 'flexibuild\migrate\controllers\MigrateController',
        ],
    ],
    ...
];
```

If you do not want to use migrate command from this extension
you may be want simply to extend you migration class from one of:
- `flexibuild\migrate\db\Migration`
- `flexibuild\migrate\db\CreateTableMigration`
