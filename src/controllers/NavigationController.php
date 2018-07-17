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
    use yii\bootstrap\Nav;


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
                    return $this->renderTemplate('navigation/menu/menulocation',['AllMenu'=>Navigation::$plugin->navigationService->GetAllNavigation()]);
        }

        /**
         * Renders index template with all the entries
         * @id,entries,title
         * @return \yii\web\Response
         *
         */
        public function actionIndex()
        {
            return $this->renderTemplate('navigation/index');
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
         return $this->renderTemplate('navigation/index',['MenuData'=>Navigation::$plugin->navigationService->GetNavigationById($NavId)]);
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
                       echo true;
                       return;
                   }
                    echo false;
                   return;
            }
        }
    }