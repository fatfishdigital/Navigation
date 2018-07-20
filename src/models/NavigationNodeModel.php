<?php
    /**
     * Created by PhpStorm.
     * User: fatfish
     * Date: 11/7/18
     * Time: 10:39 AM
     */

    namespace fatfish\navigation\models;
    use Craft;
    use craft\base\Model;

    class NavigationNodeModel extends Model
    {
        public $NodeName;
        public $NodeId;
        public $ParenNode;
        public $menuId;
        public $menuUrl;
        public $MenuOrder;



        public function rules()
        {
                return [

                            ['NodeName','required'],
                            ['NodeId','required'],
                            ['ParenNode','required'],
                            ['menuId','required'],
                            ['menuUrl','default','value'=>''],
                            ['MenuOrder','required']

                        ];
        }
    }