<?php
/**
 * [loadClass description]
 *Autoload class named by method PEAR or namespaces
 * 
 * @param  object $className Class name
 * @return void
 */
function loadClass($className) {
    $fileName = '';
    $namespace = '';

    //get directory with file if is using namespaces
    if (false !== ($lastNsPos = strripos($className, '\\'))) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    
    $fileName     .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';//get file name from both method of name (PEAR or namespaces)    
    $fullFileName = ROOT . DIRECTORY_SEPARATOR . $fileName;//determine full path to file

    //if file exist - include it, else display error message
    if (is_file($fullFileName)) {
        require_once $fullFileName;
    } else {
        echo 'Class "'.$className.'" does not exist.';
    }
}

