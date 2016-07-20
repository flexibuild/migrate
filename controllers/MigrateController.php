<?php

namespace flexibuild\migrate\controllers;

/**
 * @inheritdoc
 */
class MigrateController extends \yii\console\controllers\MigrateController
{
    /**
     * @inheritdoc
     */
    public $templateFile = '@flexibuild/migrate/views/migration.php';

    /**
     * @inheritdoc
     */
    public $generatorTemplateFiles = [
        'create_table' => '@flexibuild/migrate/views/createTableMigration.php',
        'drop_table' => '@flexibuild/migrate/views/dropTableMigration.php',
        'add_column' => '@flexibuild/migrate/views/addColumnMigration.php',
        'drop_column' => '@flexibuild/migrate/views/dropColumnMigration.php',
        'create_junction' => '@flexibuild/migrate/views/createJunctionMigration.php'
    ];

    /**
     * Creates a new migration for creating new database table.
     *
     * This command creates a new migration using the available migration template.
     * After using this command, developers should modify the created migration
     * skeleton by filling up the actual migration logic.
     *
     * ~~~
     * yii migrate/create-for-table my_table
     * ~~~
     *
     * @param string $tableName the name of the new table. This should only contain
     * letters, digits and/or underscores.
     * @throws \yii\console\Exception if the name argument is invalid.
     */
    public function actionCreateForTable($tableName)
    {
        $name = "create_{$tableName}_table";
        return $this->actionCreate($name);
    }

    /**
     * Creates a new migration for dropping the database table.
     *
     * This command creates a new migration using the available migration template.
     * After using this command, developers should modify the created migration
     * skeleton by filling up the actual migration logic.
     *
     * ~~~
     * yii migrate/create-to-drop-table my_table
     * ~~~
     *
     * @param string $tableName the name of the table that must be deleted. This should only contain
     * letters, digits and/or underscores.
     * @throws \yii\console\Exception if the name argument is invalid.
     */
    public function actionCreateToDropTable($tableName)
    {
        $name = "drop_{$tableName}_table";
        return $this->actionCreate($name);
    }

    /**
     * Creates a new migration for creating a junction database table.
     *
     * This command creates a new migration using the available migration template.
     * After using this command, developers should modify the created migration
     * skeleton by filling up the actual migration logic.
     *
     * ~~~
     * yii migrate/create-for-junction-table table_name_1 table_name_2
     * ~~~
     *
     * @param string $table1 the name of the first table. This should only contain
     * letters, digits and/or underscores.
     * @param string $table2 the name of the second table. This should only contain
     * letters, digits and/or underscores.
     * @throws \yii\console\Exception if the name argument is invalid.
     */
    public function actionCreateForJunctionTable($table1, $table2)
    {
        $name = "create_junction_{$table1}_and_{$table2}_tables";
        return $this->actionCreate($name);
    }

    /**
     * Creates a new migration for adding a new column in database table.
     *
     * This command creates a new migration using the available migration template.
     * After using this command, developers should modify the created migration
     * skeleton by filling up the actual migration logic.
     *
     * ~~~
     * yii migrate/create-to-add-column my_table new_column
     * ~~~
     *
     * @param string $tableName the name of the modified table. This should only contain
     * letters, digits and/or underscores.
     * @param string $columnName the name of the new column. This should only contain
     * letters, digits and/or underscores.
     * @throws \yii\console\Exception if the name argument is invalid.
     */
    public function actionCreateToAddColumn($tableName, $columnName)
    {
        $name = "add_{$columnName}_column_to_{$tableName}_table";
        $this->fields[] = [
            'property' => $columnName,
            'decorators' => 'string()->defaultValue(null)',
        ];
        return $this->actionCreate($name);
    }

    /**
     * Creates a new migration for dropping column from database table.
     *
     * This command creates a new migration using the available migration template.
     * After using this command, developers should modify the created migration
     * skeleton by filling up the actual migration logic.
     *
     * ~~~
     * yii migrate/create-to-drop-column my_table new_column
     * ~~~
     *
     * @param string $tableName the name of the modified table. This should only contain
     * letters, digits and/or underscores.
     * @param string $columnName the name of column that must be dropped. This should only contain
     * letters, digits and/or underscores.
     * @throws \yii\console\Exception if the name argument is invalid.
     */
    public function actionCreateToDropColumn($tableName, $columnName)
    {
        $name = "drop_{$columnName}_column_from_{$tableName}_table";
        $this->fields[] = [
            'property' => $columnName,
            'decorators' => 'string()->defaultValue(null)',
        ];
        return $this->actionCreate($name);
    }
}
