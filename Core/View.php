<?php
namespace Core;
use Helper;

class View
{
	public    $content;
    public    $header;
    protected $varDump;

	function __construct($headerFile, $contentFile) {
        $this->header  = $headerFile;
        $this->content = $contentFile;
        $this->varDump = new Helper\VarDump();   
    }

    /**
     * [prepareContent description]
     * Buferisation header, content and save it to value
     * 
     * @param  object $modelObject 
     * @param  object $userObject  
     * @return void
     */
	public function prepareContent($modelObject = null, $userObject = null) {
        //If not empty data put it to value from array
        $data = $modelObject->getAdditionalData('values');
        if (!empty($data) && is_array($data)) {
            extract($data);
        }

        //If exist such file - buferisation to header($this->header)
        if (is_file($this->header)) {
            ob_start();
            include $this->header;
            $this->header = ob_get_contents();
            ob_end_clean();
        }

        //If exist such file - buferisation to content($this->content)
        if (is_file($this->content)) {
            ob_start();
            include $this->content;
            $this->content = ob_get_contents();
            ob_end_clean();
        }
    }

    /**
     * [display description]
     * Display page with buferisated data
     * 
     * @param  object $modelObject 
     * @param  object $userObject 
     * @return void
     */
	public function display($modelObjest = null, $userObject = null) {
        $this->prepareContent($modelObjest, $userObject);
		include ROOT . '/Views/Layout.php';
        include ROOT . '/Views/Elements/Footer.php';
	}
    
}