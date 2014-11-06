<?php
/**
 * This view is used by controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */

if (preg_match('/_create_table_(.*)$/', $className, $matches)) {
    $tableName = $matches[1];
}

echo "<?php\n";
?>

use yii\db\Schema;

use flexibuild\migrate\db\CreateTableMigration;

class <?= $className ?> extends CreateTableMigration
{
    /**
     * @inheritdoc
     */
    protected function tableName()
    {
        return '<?= $tableName ?>';
    }

    /**
     * @inheritdoc
     */
    protected function tableColumns()
    {
        return [
            'id' => Schema::TYPE_PK,
            '<?= $tableName ?>_name' => Schema::TYPE_STRING.self::NOT_NULL,

            'created_at' => Schema::TYPE_INTEGER.self::NOT_NULL,
            'updated_at' => Schema::TYPE_INTEGER.self::NOT_NULL,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function tableForeignKeys()
    {
        return [
            /*
            [
                self::CFG_COLUMNS => 'user_id',
                self::CFG_REF_TABLE => 'user',
                self::CFG_REF_COLUMNS => 'id',
                self::CFG_ON_DELETE => self::FK_RESTRICT, // optional, restrict by default
                self::CFG_ON_UPDATE => self::FK_RESTRICT, // optional, restrict by default
                self::CFG_UNIQUE => false, // optional, false by default
            ],
            */
        ];
    }

    /**
     * @inheritdoc
     */
    protected function tableIndexes()
    {
        return [
            /*
            '<?= $tableName ?>_name' => true, // unique
            'created_at' => false, // non-unique

            implode(', ', [
                '<?= $tableName ?>_name',
                'created_at',
            ]) => true, // multiple columns, unique
            */
        ];
    }
}
