<?php
namespace Controllers;
use Core, Models;

class ControllerUsers extends ControllerBase
{
    /**
     *Authorisation user
     *
     * @param array $options Array with parameters
     * @return void
     */
    function actionAuthorisation($options = null)
    {
        $this->model->userAuthentication($this->postData);//Prepare data using model for display it
        
        //If user authorized - save user to session and redirect user to main page
        if (!empty($this->model->getAttribute('id'))) {
            $_SESSION['user'] = base64_encode(serialize($this->model));//Serialise and save user to session            
            header("Location: /contact/showlist"); //Redirect to another page
            exit();
        }

        $this->view->display($this->model);//Display data on page
    }

    /**
     *Registration user
     *
     * @param array $options Array with parameters
     * @return void
     */
    function actionRegistration($options = null)
    {
        $this->model->userRegistration($this->postData);//Prepare data using model for display it                

        //if registration was successful - save data to session and redirect to authorisation page
        if (!empty($this->model->getAttribute('id'))) {
            header("Location: /");//Redirect to another page
            exit();
        }

        $this->view->display($this->model);//Display data on page
    }
}