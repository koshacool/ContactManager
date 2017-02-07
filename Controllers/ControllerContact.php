<?php
namespace Controllers;
use Core, Models, Helper;

class ControllerContact extends ControllerBase
{
    /**
     **Show contacts list
     * @param array $options [Array with parameters]
     *
     * @return void
     */
    public function actionShowlist(array $options = null)
    {
        $user = unserialize(base64_decode($_SESSION['user']));//Get user from session
        $data['userId'] = $user->getAttribute('id');//Get user id

        //Save data from GET to array
        if (!empty($this->getData)) {
            $data = array_merge($data, $this->getData);
        }

        //Process data getted from post and pass some data by Get method
        if (!empty($this->postData)) {
            //Get data for sort data in SQL query
            $sorting = new Core\Sort();
            $sortValues = $sorting->sortTable($this->postData);
            extract($sortValues);
//            $this->varDump->show($this->postData);

            header("Location: /contact/showlist?currentPage=$currentPage&mainSortColumn=$mainSortColumn&sortDirectionMainColumn=$sortDirectionMainColumn&sortDirectionSecondaryColumn=$sortDirectionSecondaryColumn");//Redirect to the same page with GET data for save data in link
            exit();
        }


        $this->model->showlist($data);//Get contacts
//        $this->varDump->show($this->ajax);
        if ($this->ajax) {
            foreach ($this->model->getAdditionalData('contacts') as $contact) {
                $arrayData = $contact->getArrayProperties('last, first, email, id');
                $arrayData['phone'] = $contact->getRelationData('phone');
                $objectData[] = $arrayData;

            }
            $objectData['additionalData'] = $this->model->getAdditionalData('values');
            $objectData['additionalData']['showNumberPages'] = NUMBER_DISPLAYED_PAGES_LINKS;
//         $this->varDump->show($objectData);

            echo json_encode($objectData);
//           $this->varDump->show(json_encode($this->model));
//            echo json_encode($a);
            exit();
        }

        $this->view->display($this->model, $user);//Display data on page
    }

    /**
     **[Logout user]
     *
     * @return void
     */
    public function actionLogout()
    {
        $this->session->destroySession();
    }

    /**
     *Confirm delete contact
     *
     * @param array $options Array with parameters
     * @return void
     */
    public function actionConfirm(array $options)
    {
        $user = unserialize(base64_decode($_SESSION['user']));//Get user from session
        $this->model->setAttribute('id', $options[0]);
        $this->view->display($this->model, $user);
    }

    /**
     *[Delete contact in database]
     *
     * @param array $options [Array with parameters]
     * @return void
     */
    public function actionDelete(array  $options)
    {
        $user = unserialize(base64_decode($_SESSION['user']));//Get user from session
        $data['userId'] = $user->getAttribute('id');//Get user id
        $data['contactId'] = $options[0];//Get contact id

        if (!empty($data['contactId'])) {
            $resultRemove = $this->model->removeContact($data);
            if ($resultRemove === true) {
                header("Location: /contact/showlist");
                exit();
            } else {
                // Here must be exeption in future!!!!
                echo 'bad remove: ' . $resultRemove;
            }
        } else {
            // Here must be exeption in future!!!!
            $this->varDump->show('Bad id');
//			header("Location: /contact/showlist");
            exit();
        }
    }

    /**
     *Get contact data for display information about contact
     *
     * @param array $options Array with parameters
     * @return void
     */
    public function actionView(array $options)
    {
        $user = unserialize(base64_decode($_SESSION['user']));//Get user from session
        $this->model->setAttribute('userId', $user->getAttribute('id'));
        $this->model->setAttribute('id', $options[0]);
        $this->model->view();
        $this->view->display($this->model, $user);
    }

    /**
     *Edit/add contact in database
     *
     * @param array $options Array with parameters
     * @return void
     */
    public function actionRecord(array $options)
    {
//        $this->varDump->show($this->postData);
        $user = unserialize(base64_decode($_SESSION['user']));//Get user from session
        $this->model->setAttribute('userId', $user->getAttribute('id'));

        if (!empty($options[0])) {
            $this->model->setAttribute('id', $options[0]);
        } else {
            $this->model->setAttribute('id', 1);
        }

        //Save data from POST to array
        $data['postData'] = $this->postData;

        $validationResult = $this->model->record($data);
        if ($this->ajax) {
            echo json_encode($this->model->getRelationData('zip'));
            exit();
        }

        if ($validationResult) {
            header("Location: /contact/showlist");
            exit();
        }

        $this->view->display($this->model, $user);
    }

    /**
     *Enter emails to send
     *
     * @param array $options Array with parameters
     * @return void
     */
    public function actionEmails(array $options)
    {
//        $start = microtime(true);
        $user = unserialize(base64_decode($_SESSION['user']));//Get user from session
        $this->model->setAttribute('userId', $user->getAttribute('id'));
        $cookie = new Helper\Cookie();

        //Save url path from which a visitor came to value
        $urlPath = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);

        //If user doesn't come from EventContacts.php set empty COOKIE with emails
        if (!preg_match("/^\/contact\/select$/", $urlPath)) {
            $cookie->deleteCookie('cookieEmailsString');
        }

        //Get  data from COOKIE
        $emails = $cookie->saveCookie('cookieEmailsString');
        $arraySelectEmails = $cookie->extractCookie('selectedEmails');

        //Save selected emails from array to string value
        if (!empty($arraySelectEmails)) {
            foreach ($arraySelectEmails as $key => $value) {
                if (!isset($emails) || empty($emails)) {
                    $emails = $value;
                } else {
                    $emails .= ',' . $value;
                }
            }
        }
        $data['emails'] = $emails;

        //Save data from POST to array
        $data['postData'] = $this->postData;

        $this->model->emails($data);

        //If user push 'Select email' save data to COOKIE and redirect to EventContacts page
        if ($this->model->getAdditionalData('select')) {
            $cookie->setCookie('cookieEmailsString', $this->model->getAdditionalData('emails'));
            header("Location: /contact/select");
            exit();
        }

        if ($this->model->getAdditionalData('send')) {
            $emails = explode(',', $this->model->getAdditionalData('emails'));
            $this->model->findNewEmails($emails);
            if (empty($this->model->getAdditionalData('newEmails'))) {
                header("Location: /contact/showlist");
                exit();
            }
        }
//        echo "script execution time: ".(microtime(true) - $start);
        $this->view->display($this->model, $user);
    }

    /**
     *Select emails from saved contacts
     *
     * @param array $options Array with parameters
     * @return void
     */
    public function actionSelect(array $options)
    {

        $user = unserialize(base64_decode($_SESSION['user']));//Get user from session
        $data['userId'] = $user->getAttribute('id');//Get user id
        $cookie = new Helper\Cookie();

        //Save url path from which a visitor came to value
        $urlPath = null;
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $urlPath = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
        }

        //If user doesn't come from /contact/emeils set empty COOKIE with emails
        if (!preg_match("/^\/contact\/select$/", $urlPath)) {
            //Save data from COOKIE to array
            $data['emailsFromCookie'] = $cookie->saveCookie('cookieEmailsString');
        }

        //Save data from GET to array
        if (!empty($this->getData)) {
            $data = array_merge($data, $this->getData);
            $data['post'] = $cookie->saveCookie('post');//Save cookie to value
            $data['arrSelectedEmails'] = $cookie->extractCookie('arrSelectedEmails');
        }

        //Process data getted from post and pass some data by Get method
        if (!empty($this->postData)) {
            //Get data for sort data in SQL query
            $sorting = new Core\Sort();
            $sortValues = $sorting->sortTable($this->postData);
            extract($sortValues);

            //save data to COOKIE
            $cookie->setCookie('post', $this->postData);

            //Redirect to the same page with GET data for save data in link
            header("Location: /contact/select?currentPage=$currentPage&mainSortColumn=$mainSortColumn&sortDirectionMainColumn=$sortDirectionMainColumn&sortDirectionSecondaryColumn=$sortDirectionSecondaryColumn");
            exit();
        }

        $this->model->select($data);

        //If user push accept - save data and redirect to send emails page
        if ($this->model->getAdditionalData('accept')) {

            $data = $this->model->getAdditionalData('values');
            $emails = unserialize(base64_decode($data['emails']));
            $cookie->setCookie('selectedEmails', $data['arrSelectedEmails']);//save data to COOKIE
            $cookie->setCookie('cookieEmailsString', $emails);

            header("Location: /contact/emails");
            exit();
        }

        $data = $this->model->getAdditionalData('values');
        $cookie->setCookie('arrSelectedEmails', $data['arrSelectedEmails']);//save data to COOKIE

        if ($this->ajax) {
            foreach ($this->model->getAdditionalData('contacts') as $contact) {
                $arrayData = $contact->getArrayProperties('last, first, email, id');
                $arrayData['phone'] = $contact->getRelationData('phone');
                $objectData[] = $arrayData;
            }
            $objectData['additionalData'] = $this->model->getAdditionalData('values');
            $objectData['additionalData']['showNumberPages'] = NUMBER_DISPLAYED_PAGES_LINKS;

            echo json_encode($objectData);
            exit();
        }


        $this->view->display($this->model, $user);//Display data on page

    }

    /**
     *Save new emails
     *
     * @param array $options Array with parameters
     * @return void
     */
    public function actionSave(array $options)
    {
//        $this->varDump->show($this->postData);
        $user = unserialize(base64_decode($_SESSION['user']));//Get user from session
        $this->model->setAttribute('userId', $user->getAttribute('id'));

        //Save data from POST to array
        if (!empty($this->postData)) {
            $this->model->saveEmail($this->postData);//Save data to database
        }

        header("Location: /contact/showlist");//Redirect to main page
        exit();
    }
}