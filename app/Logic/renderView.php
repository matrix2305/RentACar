<?php
declare(strict_types = 1);
namespace App\Logic;

use Exception;

class renderView
{
    private $data;
    private $renderLayout;
    private $renderView;
    public  $content;


    public function __construct($layoutName, $viewName)
    {
        $this->data = array();
        $layout = 'resources/view/layouts/'.$layoutName.'.phtml';
        $layout2 = '../resources/view/layouts/'.$layoutName.'.phtml';
        $view = 'resources/view/'.$viewName.'.phtml';
        $view2 = '../resources/view/'.$viewName.'.phtml';
        if (file_exists($layout)) {
            $this->renderLayout = $layout;
        } elseif (file_exists($layout2)){
            $this->renderLayout = $layout2;
        }else {
            throw new Exception('Layout ' . $layoutName . ' not found!');
            exit();
        }

        if(file_exists($view)){
            $this->renderView = $view;
        }elseif (file_exists($view2)){
            $this->renderView = $view2;
        } else{
            throw new Exception('View ' . $viewName . ' not found!');
            exit();
        }


    }


    public function assignVariable($variableName, $variableValue)
    {
        $this->data[$variableName] = $variableValue;
    }

    public function renderView(){
        ob_start();
        extract($this->data);
        include_once ($this->renderView);
        $this->content = ob_get_contents();
        ob_get_clean();
    }


    public function __destruct()
    {
        extract($this->data);
        $this->renderView();
        include_once ($this->renderLayout);
    }
}
