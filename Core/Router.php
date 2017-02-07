<?php
namespace Core;
use Controllers, Helper;

class Router
{
    private $varDump;

    function __construct() {
        $this->varDump = new Helper\VarDump;
    }

    /**
     *Get request(URL) string
     *
     * @return string
     */
    private function getURL()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     *Define names for Controller, Model and Parameters and call its
     *
     * @return void
     */
    public function start()
    {
        //Default parameters
        $controllerName = 'ControllerUsers';
        $actionName     = 'actionAuthorisation';
        $model 			= 'Models\ModelUsers';
        $headerFile     = ROOT . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Elements' . DIRECTORY_SEPARATOR . 'HeaderUsers.php';
        $contentFile 	= ROOT . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Users' . DIRECTORY_SEPARATOR . 'Authorisation.php';
        $parameters 	= array(); 
        $url 			= $this->getURL();
        $segments 		= explode('/', $url);

        array_shift($segments);//Remove first empty value

        //Get controller name
        if (!empty($segments[0])) {
            $controllerName = ucfirst(array_shift($segments));
            $model 			= 'Models\Model' . $controllerName;
            $contentFile    =  $controllerName;
            $controllerName = 'Controller' . $controllerName;
        }

        //Get action name
        if (!empty($segments[0])) {
            $actionName  = ucfirst(array_shift($segments));
            $headerFile  = ROOT . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Elements' . DIRECTORY_SEPARATOR . 'Header' . $contentFile . '.php';
            $contentFile = ROOT . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $contentFile . DIRECTORY_SEPARATOR . $actionName . '.php';
            $actionName  = 'action' . $actionName;
        }

        //Get parameters from url
        if (!empty($segments)) {
            $parameters = $segments;
        }

        $controllerFile = ROOT . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $controllerName . '.php';//Way to controller file

        //If isset such file - connect it or displey error page
        if (file_exists($controllerFile)) {
            include_once($controllerFile);
        } else {
            self::errorPage($url);
        }

        $controllerName   = 'Controllers\\' . $controllerName;
        $controllerObject = new $controllerName($url, $headerFile, $contentFile, $model);//Create controller object
        //$this->varDump->show($parameters);

        //Call controller method with params, if such isset or display error page
        if (method_exists($controllerObject, $actionName)) {
            call_user_func_array(array($controllerObject, $actionName), array($parameters));
        } else {
            self::errorPage($url);
        }
    }

    /**
     *Define names for Controller, Model and Parameters and call its when bad url request
     *
     * @return void
     */
    private static function errorPage($url)
    {
        $controllerName   = 'Controllers\\ControllerError';
        $actionName 	  = 'actionShow';
        $parameters       = array();
        $headerFile       = ROOT . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Elements' . DIRECTORY_SEPARATOR . 'HeaderUsers.php';
        $contentFile 	  = ROOT . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Elements' . DIRECTORY_SEPARATOR . 'ErrorPage.php';
        $controllerObject = new $controllerName($url, $headerFile, $contentFile);
        call_user_func_array(array($controllerObject, $actionName), $parameters);
        exit();
    }
}