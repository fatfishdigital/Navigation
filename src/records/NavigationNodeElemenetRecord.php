<?php
    /**
     * Created by PhpStorm.
     * User: fatfish
     * Date: 11/7/18
     * Time: 12:20 PM
     */

    namespace fatfish\navigation\records;
    use yii\db\ActiveRecord;
    class NavigationNodeElemenetRecord extends ActiveRecord
    {
       public static function tableName()
       {
           return '{{%navigations_MenuItems%}}';
       }
    }