<?php

namespace flexibuild\migrate\controllers;

use yii\console\controllers\MigrateController as BaseMigrateController;

/**
 * @inheritdoc
 */
class MigrateController extends BaseMigrateController
{
    /**
     * @var string the template file for generating new migrations.
     * This can be either a path alias (e.g. "@app/migrations/template.php")
     * or a file path.
     * Defaults:
     * - @flexibuild/migrate/views/default.php for create action.
     * - @flexibuild/migrate/views/createTable.php for create-for-table action.
     */
    public $templateFile = null;

    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        return array_merge(
            parent::options($actionID),
            ($actionID == 'create-for-table') ? ['templateFile'] : [] // action createForTable
        );
    }

    /**
     * Creates a new migration.
     *
     * This command creates a new migration using the available migration template.
     * After using this command, developers should modify the created migration
     * skeleton by filling up the actual migration logic.
     *
     * ~~~
     * yii migrate/create create_user_table
     * ~~~
     *
     * @param string $name the name of the new migration. This should only contain
     * letters, digits and/or underscores.
     * @throws Exception if the name argument is invalid.
     */
    public function actionCreate($name)
    {
        if ($this->templateFile === null) {
            $this->templateFile = '@flexibuild/migrate/views/default.php';
        }
        parent::actionCreate($name);
    }

    /**
     * Creates a new migration for creating new database table.
     *
     * This command creates a new migration using the available migration template.
     * After using this command, developers should modify the created migration
     * skeleton by filling up the actual migration logic.
     *
     * ~~~
     * yii migrate/create-for-table post
     * ~~~
     *
     * @param string $tableName the name of the new table. This should only contain
     * letters, digits and/or underscores.
     * @throws \yii\console\Exception if the name argument is invalid.
     */
   public function actionCreateForTable($tableName)
    {
        if ($this->templateFile === null) {
            $this->templateFile = '@flexibuild/migrate/views/createTable.php';
        }
        $name = "create_table_$tableName";
        $this->actionCreate($name);
    }
}
