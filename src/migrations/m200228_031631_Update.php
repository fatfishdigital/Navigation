<?php

namespace fatfish\navigation\migrations;

use Craft;
use craft\db\Migration;

/**
 * m200228_031631_Update migration.
 */
class m200228_031631_Update extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Place migration code here...
        $this->addColumn('navigations_MenuItems','UniqueId','string');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200228_031631_Update cannot be reverted.\n";
        return false;
    }
}
