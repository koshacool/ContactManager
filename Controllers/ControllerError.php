<?php
namespace Controllers;
use Core;

/**
 * Created by PhpStorm.
 * User: kosha
 * Date: 05.10.2016
 * Time: 22:14
 */
class ControllerError extends ControllerBase
{
    public function actionShow(array $options = null)
    {        
        $this->view->display();
    }
}