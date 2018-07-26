<?php
    /**
     * Created by PhpStorm.
     * User: fatfish
     * Date: 16/7/18
     * Time: 4:57 PM
     */

    namespace fatfish\navigation\variables;
    use fatfish\navigation\Navigation;
    use Craft;
    class NavigationVariable
    {


        public function render($MenuName, array $options=[])
        {
                Craft::$app->view->setTemplateMode('cp');
                $template= Craft::$app->view->renderTemplate('navigation/_renderMenu/index',['MenuNodes'=>Navigation::$plugin->navigationService->GetNavigationByName($MenuName),'menucss'=>$options]);
                echo $template;

        }
        public function renderChildren($NodeId)
        {

          return Navigation::$plugin->navigationService->GetChild($NodeId);



        }

        public function getRawNav($HandleName)
        {
            return Navigation::$plugin->navigationService->GetNavigationByName($HandleName);
        }



    }