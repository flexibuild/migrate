<?php
/**
 * This view is used by controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */

echo "<?php\n";
?>

use flexibuild\migrate\db\Migration;

class <?= $className ?> extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        echo "<?= $className ?> cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
