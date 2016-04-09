<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */
/* @var $table string the name table */
/* @var $fields array the fields */

echo "<?php\n";
?>

use flexibuild\migrate\db\Migration;

class <?= $className ?> extends Migration
{
    private $_table = '<?= $table ?>';

    /**
     * @inheritdoc
     */
    public function up()
    {
<?php foreach ($fields as $field): ?>
        $this->dropColumn($this->_table, <?= "'" . $field['property'] . "'" ?>);
<?php endforeach; ?>
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
<?php foreach ($fields as $field): ?>
        $this->addColumn($this->_table, <?= "'" . $field['property'] . "', \$this->" . $field['decorators'] ?>);
<?php endforeach; ?>
    }
}
