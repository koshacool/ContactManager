<?php
namespace Controllers;
use Core, Helper;

class ControllerBase extends Core\ControllerAbstract
{	
	/*array with classes name for create objects
    * or array with method names for get data
    *array key is name variable for save created object,
    *array value is class name for create object
    */
    private   $helperNames = array(        
        'varDump' => 'Helper\VarDump',
        'session' => array(
            //key is class name, value is parameters for give it to object            
            'Helper\Session' => 'url, allowUrls'),
        'getPostData' => array(
            //key is class name
        	'Helper\CheckGetPostData' => array(
        		//key name is name variable, value is name of function for get data and save it to variable
        		'getData'  => 'getToArray',
            	'postData' => 'postToArray',
                'ajax'     => 'checkAjax')));


	//Urls allowed for not autorized users
    private $allowUrls = array(
        '/'                   => '/^\/$/', 
        '/users'               => '/^\/users$/',
        '/users/authorisation' => '/^\/users\/authorisation$/',
        '/users/registration'  => '/^\/users\/registration$/');

    protected $ajax;


    function __construct($url, $headerFile, $contentFile = null, $model = null)
    { 
    	parent::__construct($url, $headerFile, $contentFile, $model);
        $this->getClassAttribute($this->url);
    }


    /**
     * [getClassAttribute description]
     * Connect Helpers and their attributes.
     * All Helpers defined in array $helperNames;
     * 
     * @param  [string] $url [requested url]
     * @return [void] 
     */
    protected function getClassAttribute($url) {
    	/*save object to class attribute or
        use object method to save data in class attribute*/
        foreach ($this->helperNames as $attributeName => $objectName) {
            /*If value is array - create object and use function name from array 
            to save data in class attribute*/
            if (is_array($objectName)) {                
                $className = key($objectName); //get class name               
                
                //if value is array - create object and call functionName from array
                if (is_array($objectName[$className])) {
                    $this->$attributeName = new $className();
                    foreach ($objectName[$className] as $attribute => $functionName) {                        
                        $this->$attribute = call_user_func(array($this->$attributeName, $functionName));//call object function for save data in class attribute
                    }                    
                } else {
                    /*if value isn't array - get params from this value,
                    save it to array and create object with this array param*/
                    $paramNames = explode(',', $objectName[$className]);
                    //Put values name to array
                    foreach ($paramNames as $param) { 
                        $param              = trim($param);    
                        $parameters[$param] = $this->$param;
                    }
                    $this->$attributeName = new $className($parameters);                    
                }                
                unset($object);
            } else {               
               $this->$attributeName = new $objectName;//save object to class attribute
            }
        }
    }
}