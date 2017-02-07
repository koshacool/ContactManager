<?php
namespace Helper;
class CheckGetPostData
{

    /**
     * Save data from POST to array
     *
     * @param $connect connect to DataBase
     * @return array
     */
    public function postToArray($array = null)
    {
        //put data from POST to array
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $value = $this->cleanData($value);
                $array[$key] = $value;
            }
        }
        return $array;
    }

    /**
     * Save data from GET to array
     *
     * @param $connect connect to DataBase
     * @return array
     */
    public function getToArray($array = null)
    {
        //put data from GET to array
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $value = $this->cleanData($value);
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * Clean data from php and sql injections
     *
     * @param string $value Value to clean
     * @param $connect Connect to DataBase
     * @return string
     */
    private function cleanData($value)
    {       
        $value = trim($value); // delete whitespace        
        $value = stripslashes($value);//Un-quotes a quoted string        
        $value = strip_tags($value);//Strip HTML and PHP tags from a string        
        $value = htmlspecialchars($value, ENT_QUOTES);//Convert special characters to HTML entities
        //@$value = mysqli_real_escape_string($connect, $value);//Escapes special characters in a string for use in an SQL statement

        return $value;
    }

    /**
     * Check isset ajax query
     *
     * @return boolean
     */
    public function checkAjax() {
        return (isset($_SERVER['HTTP_ACCEPT']) && ($_SERVER['HTTP_ACCEPT'] == 'application/ajax'));
    }

}