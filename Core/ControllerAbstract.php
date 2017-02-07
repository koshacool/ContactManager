<?php
namespace Core;

abstract class ControllerAbstract
{    
    protected $session;
    protected $varDump;
    protected $view;
    protected $model;
    protected $getData;
    protected $postData; 
    protected $url;  
    protected $getPostData;     

    function __construct($url, $headerFile, $contentFile = null, $model = null)
    {   
        $this->url  = $url;
        $this->view = new View($headerFile, $contentFile);
        
        if (!empty($model)) {
            $this->model = new $model;
        }        
    }

    abstract protected function getClassAttribute($url);
}