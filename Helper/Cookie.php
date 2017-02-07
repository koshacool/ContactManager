<?php
namespace Helper;
/**
 * Created by PhpStorm.
 * User: kosha
 * Date: 04.10.2016
 * Time: 22:31
 */
class Cookie
{
    /**
     *Save cookie to value and then remove COOKIE
     *
     * @param string COOKIE's name
     *
     * @return object
     */
    public function extractCookie($cookieName)
    {
        //Save cookie to value and then remove COOKIE
        if (isset($_COOKIE[$cookieName]) && !empty($_COOKIE[$cookieName])) {
            $data = $this->saveCookie($cookieName);
            $this->deleteCookie($cookieName);
        } else {
            $data = null;
        }
        
        return $data;
    }

    /**
     *Save data to cookie
     *
     * @param string COOKIE's name
     * @param object Data for save it in cookie
     * @return void
     */
    public function setCookie($cookieName, $value)
    {
        setcookie($cookieName, base64_encode(serialize($value)), time() + 10800);
    }

    /**
     *Save COOKIE to string value
     *
     * @param string COOKIE's name
     * @return string
     */
    public function saveCookie($cookieName)
    {
        if (isset($_COOKIE[$cookieName]) && !empty($_COOKIE[$cookieName])) {
          $value = $_COOKIE[$cookieName];

            if ($this->isJSON($value)) {
                return json_decode($value, true);
            }
            return unserialize(base64_decode($value));
        }
        return null;
    }

    /**
     *Set empty COOKIE(delete COOKIE)
     *
     * @param string COOKIE's name
     * @return void
     */
    public function deleteCookie($cookieName)
    {
        setcookie($cookieName, '');
        unset($_COOKIE[$cookieName]);
    }

    /**
     *Check string for json format
     *
     * @param string $string
     * @return boolean
     */
    function isJSON($string) {
        if ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) {
            return true;
        }
        return false;
    }

}