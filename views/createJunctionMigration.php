<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */
/* @var $table string the name table */
/* @var $fields array the fields */
/* @var $foreignKeys array the foreign keys */

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
<?php foreach ($fields as $field): ?>
<?php if (!empty($field['decorators'])): ?>
            <?= "'{$field['property']}' => \$this->{$field['decorators']}" ?>->notNull(),
<?php endif; ?>
<?php endforeach; ?>
            'created_at' => $this->integer()->notNull(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function tableForeignKeys()
    {
        return [
<?php foreach ($foreignKeys as $column => $fkData): ?>
            [
                self::CFG_COLUMNS => '<?= $column ?>',
                self::CFG_REF_TABLE => '<?= $fkData['relatedTable'] ?>',
                self::CFG_REF_COLUMNS => 'id',
                self::CFG_ON_DELETE => self::FK_CASCADE, // optional, restrict by default
                self::CFG_ON_UPDATE => self::FK_RESTRICT, // optional, restrict by default
                self::CFG_UNIQUE => false, // optional, false by default
            ],
<?php endforeach; ?>
        ];
    }

    /**
     * @inheritdoc
     */
    protected function tableIndexes()
    {
        return [
            implode(' ', [
<?php foreach (array_keys($foreignKeys) as $column): ?>
                '<?= $column ?>',
<?php endforeach; ?>
            ]) => true, // unique
<?php foreach (array_keys($foreignKeys) as $column): ?>
            implode(' ', [
                '<?= $column ?>',
                'created_at',
            ]) => false, // non-unique
<?php endforeach; ?>
        ];
    }
}
