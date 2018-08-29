<?php
    /**
     * Created by PhpStorm.
     * User: fatfish
     * Date: 29/6/18
     * Time: 12:21 PM
     */

    namespace fatfish\navigation\controllers;
    use Cake\Core\Retry\CommandRetry;
    use Craft;
    use fatfish\navigation\models\NavigationModel;
    use fatfish\navigation\models\NavigationNodeModel;
    use fatfish\navigation\Navigation;
    use craft\web\Controller;
    use fatfish\navigation\records\NavigationNodeElemenetRecord;
    use fatfish\navigation\records\NavigationRecord;
    use function Sodium\crypto_aead_aes256gcm_decrypt;
    use Symfony\Component\DomCrawler\Tests\CrawlerTest;
    use yii\bootstrap\Nav;
    use craft\web\View;


    /**
     * Class NavigationController
     * @package fatfish\navigation\controllers
     */
    class NavigationController extends Controller
    {
        public $model;
        public $NavigationNodeModel;


        /**
         * Manage Location of each menu item
         * Can also have custom url for each menu item.
         * @return \yii\web\Response
         */

        public function actionLocation()
        {
                    return $this->renderTemplate('craftnavigation/menu/menulocation',['AllMenu'=>Navigation::$plugin->navigationService->GetAllNavigation()]);
        }

        /**
         * Renders index template with all the entries
         * @id,entries,title
         * @return \yii\web\Response
         *
         */
        public function actionIndex()
        {
            return $this->renderTemplate('craftnavigation/index',['MenuList'=>Navigation::$plugin->navigationService->GetMenuList()]);
        }


        /**
         * Save menu items with current site id.
         * @param NavigationModel $model
         */
        public function actionSave()
        {
            $this->model = new NavigationModel();
            $this->NavigationNodeModel = new NavigationNodeModel();
            if(Craft::$app->request->isAjax)
            {

            $this->model->siteId = Craft::$app->request->getBodyParam('menuname')[0]['siteId'];
            $this->model->MenuName = Craft::$app->request->getBodyParam('menuname')[0]['menuname'];
            $this->model->MenuHtml = Craft::$app->request->getBodyParam('menuhtml');


              if($this->FindNodeMenuItem(Craft::$app->request->getBodyParam('menuArray'),Navigation::$plugin->navigationService->saveNavigation($this->model,Craft::$app->request->getBodyParam('id'))))
               {
                echo true;

              }
            }


        }


        /**
         * @param $menuItems
         * Will scan for each menu item for parent and child node.
         *
         * @param $MenuId
         */
        public function FindNodeMenuItem($menuItems,$MenuId)
        {



           foreach ($menuItems as $menuItem):
                if(isset($menuItem['id']) && (!is_null($menuItem['id']) || !empty($menuItem['id'])))
                {
                    $this->NavigationNodeModel->NodeName=$menuItem['title'];
                   $this->NavigationNodeModel->NodeId = (int)$menuItem['id'];
                   $this->NavigationNodeModel->ParenNode = (int)$menuItem['parent_id'];
                   $this->NavigationNodeModel->menuId = $MenuId;
                   $this->NavigationNodeModel->menuUrl = $menuItem['url'];
                   $this->NavigationNodeModel->MenuOrder = array_search($menuItem,$menuItems);
                    if($this->NavigationNodeModel->validate())
                    {
                      Navigation::$plugin->navigationService->SaveNodeElement($this->NavigationNodeModel);

                    }
                    else
                    {
                        return false;
                    }

                }

               endforeach;

            return true;
        }


        /**
         * @return html
         */
        public function actionEdit($NavId)
        {

         return $this->renderTemplate('craftnavigation/index',['MenuData'=>Navigation::$plugin->navigationService->GetNavigationById($NavId),'Menu'=>Navigation::$plugin->navigationService->GetMenuList()]);
        }

        /**
         *
         */
        public function actionDelete()
        {
            if(Craft::$app->request->isAjax)
            {

                   if( Navigation::$plugin->navigationService->DeleteNavById( Craft::$app->request->getBodyParam('id')))
                   {
                      return $this->asJson(true);
                   }
                return $this->asJson(false);
            }
        }

        /**
         *
         */
        public function actionMenunodedelete()
        {
            if(Craft::$app->request->isAjax)
            {

                $MenuNode = new NavigationNodeElemenetRecord();
                $NodeId = (int)str_replace('menuItem_','',Craft::$app->request->getBodyParam('id'));
               if($MenuNode::deleteAll(['NodeId'=>$NodeId]))
               {
                   echo true;
               }
               return true;

            }

        }
        public function actionMenusave()
        {
            if(Craft::$app->request->isAjax)
            {
                $NavigationModel = New NavigationModel();
                $NavigationModel->MenuName = Craft::$app->request->getBodyParam('data');
                $NavigationModel->siteId = Craft::$app->request->getBodyParam('siteid');
                $NavigationData=  Navigation::$plugin->navigationService->saveNavigationName($NavigationModel);
                return $this->asJson($NavigationData);


            }


        }
        /*
        Adding menu rename feature, In past we only can delete Navigation Title but cannot rename it.
        */
          public function actionRename()
        {
            if(Craft::$app->request->isAjax)
            {
               $id = Craft::$app->request->getBodyParam('id');
               $name = Craft::$app->request->getBodyParam('name');
               $Navigation = NavigationRecord::findOne(['id'=>$id]);
               $Navigation->MenuName = $name;
               $Navigation->save();
                echo true;
            }
        }
    }
