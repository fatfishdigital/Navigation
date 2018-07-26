<?php
/**
 * Navigation plugin for Craft CMS 3.x
 *
 * Craft navigation for the website.
 *
 * @link      https://fatfish.com.au
 * @copyright Copyright (c) 2018 Fatfish
 */

namespace fatfish\navigation\services;
use Craft;
use craft\base\Component;
use fatfish\navigation\models\NavigationModel;
use fatfish\navigation\models\NavigationNodeModel;
use fatfish\navigation\records\NavigationNodeElemenetRecord;
use fatfish\navigation\records\NavigationRecord;
use function GuzzleHttp\Promise\all;
use yii\bootstrap\Nav;


/**
 * NavigationService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Fatfish
 * @package   Navigation
 * @since     1.0.0
 */
class NavigationService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     Navigation::$plugin->navigationService->exampleService()
     *
     * @return mixed
     */


    /**
     *
     * @return menu Id
     * checks whether menu already exist in database or not if it exist return id else return false
     * to create new menu item
     */

   public function findByName($name)
   {
       $MenuId = NavigationRecord::find()->select('id')->where(['MenuName'=>$name])->all();
       if(isset($MenuId[0]['id']))
       {
           return $MenuId[0]['id'];
       }
       else
       {
           return false;
       }
   }

    /**
     * @param NavigationModel $model
     */
    public function saveNavigation(NavigationModel $model,$id)
    {


        $menduid=$this->findByName($model->MenuName);

        if($menduid || (!is_null($id) && !isset($id))) {

            $NavigationRecord = NavigationRecord::findOne($menduid);
            $NavigationRecord->siteId = $model->siteId;
            $NavigationRecord->MenuName = $model->MenuName;
            $NavigationRecord->MenuHtml = $model->MenuHtml;
             $NavigationRecord->update(true);
            return $menduid;
        }else
            {
                $NavigationRecord = new NavigationRecord();
                $NavigationRecord->siteId = $model->siteId;
                $NavigationRecord->MenuName = $model->MenuName;
                $NavigationRecord->MenuHtml = $model->MenuHtml;
            $NavigationRecord->save(true);
            return ($NavigationRecord->getAttribute('id'));
        }
    }


    /**
     * checks if submenu already exist in database if it exist return the id to update else return false
     * @param $nodeId
     * @return bool
     */
    public function CheckNodeElementId($nodeId)
    {
        $NodeElementId = NavigationNodeElemenetRecord::find()->select('id')->where(['NodeId'=>$nodeId])->all();
        if(isset($NodeElementId[0]['id']))
        {
            return $NodeElementId[0]['id'];
        }
        return false;
    }





    /**
     * @param NavigationNodeModel $model
     */
    public function SaveNodeElement(NavigationNodeModel $model)
    {
            $id=$this->CheckNodeElementId($model->NodeId);

            if(!$id)
            {
                $record                  = new NavigationNodeElemenetRecord();
                $record->NodeName        = $model->NodeName;
                $record->NodeId          = $model->NodeId;
                $record->ParenNode       = $model->ParenNode;
                $record->menuId          = $model->menuId;
                $record->menuUrl         = $model->menuUrl;
                $record->MenuOrder       = $model->MenuOrder;
                $record->save(true);

            }
            else
            {
                $record = NavigationNodeElemenetRecord::findOne($id);
                $record->NodeName        = $model->NodeName;
                $record->NodeId          = $model->NodeId;
                $record->ParenNode       = $model->ParenNode;
                $record->menuId          = $model->menuId;
                $record->menuUrl         = $model->menuUrl;
                $record->MenuOrder       = $model->MenuOrder;
                 $record->update(true);
            }

    }

    /**
     * @return static[]
     */
    public function GetAllNavigation()
    {
        $record = new NavigationRecord();
        return  $record::find()->all();
    }

    /**
     * @param $id
     *  returns Navigation records from database
     * @return array|\yii\db\ActiveRecord[]
     */
    public function GetNavigationById($id)
    {
        $NavigationRecord = new NavigationRecord();
        return $NavigationRecord::find()->where(['id'=>$id])->all();
    }

    /**
     * @param $id
     * @return bool
     *             this will delete parent menu and child menu
     *             TODO : i think there will be problem lets suppose there is no parent menu and but there exist a child menu which have prarent but its orphan now :D :D :D
     *
     */
    public function DeleteNavById($id)
    {
        $NavigationRecord = new NavigationRecord();
        $NavigationNodeElementRecord = new NavigationNodeElemenetRecord();
       if($NavigationRecord::deleteAll(['id'=>$id]) && $NavigationNodeElementRecord::deleteAll(['menuid'=>$id]))
       {
           return true;
       }
       return false;


    }
    /*
     * get Navigation by name
     */
    /**
     * @param $handleName
     * @return array|\yii\db\ActiveRecord[]
     */
    public function GetNavigationByName($handleName)
    {

        $NavigationNodeElementRecord = new NavigationNodeElemenetRecord();
        return $NavigationNodeElementRecord::find()->where(['menuId'=>(int)$this->findByName($handleName)])->orderBy(['MenuOrder'=>'asc'])->all();
    }
    public function GetChild($nodeId)
    {
        $NavigationNodeElementRecord = new NavigationNodeElemenetRecord();
        return $NavigationNodeElementRecord::find()->where(['ParenNode'=>(int)$nodeId])->orderBy(['MenuOrder'=>'asc'])->all();
    }
    /*
     * param Navigation Model
     * returns Last saved navigation data.
     */
    public function saveNavigationName($model)
    {
        $NavigationRecord = new NavigationRecord();
        $NavigationRecord->siteId = $model->siteId;
        $NavigationRecord->MenuName = $model->MenuName;
        $NavigationRecord->MenuHtml = $model->MenuHtml;
        $NavigationRecord->save();
        return $NavigationRecord->getAttribute('id');

    }

    /*
     * list menu id and name
     */
        public function GetMenuList()
        {
                        return NavigationRecord::find()->orderBy(['id'=>'desc'])->all();

        }
}
