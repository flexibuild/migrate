<?php
/**
 * This view is used by controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */

echo "<?php\n";
?>

use yii\db\Schema;

use console\base\db\Migration;

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
}
