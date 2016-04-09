<?php
/**
 * This view is used by controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */
/* @var $table string the name table */
/* @var $fields array the fields */


echo "<?php\n";
?>

use flexibuild\migrate\db\CreateTableMigration;

class <?= $className ?> extends CreateTableMigration
{
    /**
     * @inheritdoc
     */
    protected function tableName()
    {
        return '<?= $table ?>';
    }

    /**
     * @inheritdoc
     */
    protected function tableColumns()
    {
        return [
<?php foreach ($fields as $field): ?>
            '<?= $field['property'] ?>' => $this-><?= $field['decorators'] . ",\n"?>
<?php endforeach; ?>

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
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
            '<?= $table ?>_name' => true, // unique
            'created_at' => false, // non-unique

            implode(', ', [
                '<?= $table ?>_name',
                'created_at',
            ]) => true, // multiple columns, unique
            */
        ];
    }
}
