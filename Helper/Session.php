<?php
namespace Helper;

class Session
{       
    function __construct(array $values)
    {           
        //If session didn't start - start it
        if (session_status() !== PHP_SESSION_ACTIVE) {
        	session_start();
        }      
        
        $this->checkUserAuthorization($values);//Check user authorization   
    }

    /**
     **Check authorization user
     * @param $url current url
     * @return void
     */
    private function checkUserAuthorization(array $values)
    {
        extract($values);
        
        if (array_key_exists($url, $allowUrls) && preg_match($allowUrls[$url], $url)) {
            
            //if user authorized and url is authorization or registration - redirect to main
            if (isset($_SESSION['user'])) {
                header("Location: /contact/showlist");
                exit();
            }
        } else {
            
            //if user not authorized and url isn't authorization or registration - redirect to index
            if (empty($_SESSION['user'])) {
                header("Location: /");
                exit();
            }
        }         
    }

    /**
     **Destroy session
     *
     * @return void
     */
    public function destroySession()
    {
        session_unset();
        session_destroy();
        header("Location: /");
        exit();
    }

    /**
     **Save data to session
     *
     * @param array $data Array with values o
     * @return void
     */
    public function setSession($data)
    {        
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }        
    }

    /**
     **Get value from session
     *
     * @param string $nameValue 
     * @return string
     */
    public function getValue($nameValue)
    {   
        //If value isset - return the value, else return null
        if (isset($_SESSION[$nameValue])) {
            return $_SESSION[$nameValue];
        } else {
            //Here must be exeption in future
            return null;
        }        
    }

}