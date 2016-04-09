<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */
/* @var $table string the name table */
/* @var $field_first string the name field first */
/* @var $field_second string the name field second */

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
            'id' => $this->primaryKey(),
            '<?= $field_first ?>_id' => $this->integer()->notNull(),
            '<?= $field_second ?>_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function tableForeignKeys()
    {
        return [
            [
                self::CFG_COLUMNS => '<?= $field_first ?>_id',
                self::CFG_REF_TABLE => '<?= $field_first ?>',
                self::CFG_REF_COLUMNS => 'id',
                self::CFG_ON_DELETE => self::FK_CASCADE, // optional, restrict by default
                self::CFG_ON_UPDATE => self::FK_RESTRICT, // optional, restrict by default
                self::CFG_UNIQUE => false, // optional, false by default
            ],
            [
                self::CFG_COLUMNS => '<?= $field_second ?>_id',
                self::CFG_REF_TABLE => '<?= $field_second ?>',
                self::CFG_REF_COLUMNS => 'id',
                self::CFG_ON_DELETE => self::FK_CASCADE, // optional, restrict by default
                self::CFG_ON_UPDATE => self::FK_RESTRICT, // optional, restrict by default
                self::CFG_UNIQUE => false, // optional, false by default
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function tableIndexes()
    {
        return [
            implode(' ', [
                '<?= $field_first ?>',
                '<?= $field_second ?>',
            ]) => true, // unique
            implode(' ', [
                '<?= $field_first ?>',
                'created_at',
            ]) => false, // non-unique
            implode(' ', [
                '<?= $field_second ?>',
                'created_at',
            ]) => false, // non-unique
        ];
    }
}
