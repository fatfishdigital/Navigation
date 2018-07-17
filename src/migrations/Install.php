<?php
/**
 * Navigation plugin for Craft CMS 3.x
 *
 * Craft navigation for the website.
 *
 * @link      https://fatfish.com.au
 * @copyright Copyright (c) 2018 Fatfish
 */

namespace fatfish\navigation\migrations;

use fatfish\navigation\Navigation;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * Navigation Install Migration
 *
 * If your plugin needs to create any custom database tables when it gets installed,
 * create a migrations/ folder within your plugin folder, and save an Install.php file
 * within it using the following template:
 *
 * If you need to perform any additional actions on install/uninstall, override the
 * safeUp() and safeDown() methods.
 *
 * @author    Fatfish
 * @package   Navigation
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * This method contains the logic to be executed when applying this migration.
     * This method differs from [[up()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables() && $this->createMenuItemTables()) {

            Craft::$app->db->schema->refresh();

        }


        return true;
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[down()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates the tables needed for the Records used by the plugin
     *
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

    // navigation_navigationrecord table
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%navigation_navigations%}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
//            $this->createMenuItemTables();
            $this->createTable(
                '{{%navigation_navigations}}',
                [
                    'id' => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid(),
                // Custom columns in the table
                    'siteId' => $this->integer()->notNull(),
                    'MenuName' => $this->string(255)->notNull()->defaultValue(''),
                    'MenuHtml'=>$this->text(),
                ]
            );
        }


        return $tablesCreated;
    }


    protected function removeTables()
    {

        $this->dropTableIfExists('{{%navigation_navigations%}}');
        $this->dropTableIfExists('{{%navigations_MenuItems%}}');
    }
    protected function createMenuItemTables()
    {
         $this->createTable('{{%navigations_MenuItems%}}',[

                'id'=>$this->primaryKey(),
                'NodeName'=>$this->string(),
                'NodeId'=>$this->string(),
                'ParenNode'=>$this->integer(),
                'menuId'=>$this->integer(),
                'menuUrl'=>$this->string(255),
                'uid'=>$this->uid(),
                'MenuOrder'=>$this->integer(),
                'dateCreated'=>$this->dateTime()->notNull(),
                'dateUpdated'=>$this->dateTime()->notNull()
                ]);
            return true;
    }
}
