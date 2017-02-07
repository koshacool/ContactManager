<?php
namespace Models;

use Core, Helper;

class ModelUsers extends ModelBase
{

    protected $login;
    protected $password;

    function __construct($id = null, $login = null, $password = null)
    {
        parent::__construct();
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * [save description]
     * Save Data to DB
     *
     * @param  array $what What add to DB
     * @param  array $where params for save
     * @return void
     */
    protected function save($queryParams)
    {
        return $this->query->sqlQueryInsert($queryParams);
    }

    /**
     *Authentication user
     *
     * @param array $data data recieved from post
     * @return array
     */
    public function userAuthentication($data)
    {
        //If data is not empty - check it
        if (!empty($data)) {
            //Validation data and get message if not valid data
            $this->validateData($data);

            //If data is valid check user data in DB
            if (!$this->countError) {
                $this->setAttributes($data);

                //Find all users with entered login
                $arrParams['what'] = array('selectAll' => '*');
                $arrParams['where'] = array('Login' => $this->login);

                $result = $this->find($this->prepareQueryParams($arrParams));

                //if such login not exist display message
                if (!mysqli_num_rows($result)) {
                    $this->errorMessages['login'] = "User doesn't exist.";
                }

                while ($res = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    //Check password for entered login
                    if (password_verify($this->password, $res['Password'])) {
                        $this->id = $res['user_id'];
                    } else {
                        $this->errorMessages['login'] = "You entered bad password.";
                    }
                }
            }
        } else {
            //If data is empty generate its data for page
            $this->errorMessages = array('login' => null,
                'password' => null);
        }
    }

    /**
     *Registration user
     *
     * @param array $data data recieved from post
     * @return array
     */
    public function userRegistration($data)
    {
        //If data is not empty - check it
        if (!empty($data)) {
            //Validation data
            $this->validateData($data);

            //Check repeat password
            if (!$this->countError && ($data['password'] != $data['repeatPassword'])) {
                $this->errorMessages['repeatPassword'] = 'Bad repeat password';
                $this->countError = 1;
            }

            //If data is valid, check isset such data in DB
            if (!$this->countError) {
                $this->setAttributes($data);

                $arrParams['what'] = array('selectAll' => '*');
                $arrParams['where'] = array('Login' => $this->login);
                $result = $this->find($this->prepareQueryParams($arrParams));//Check issed such entered data in DB
                $res = mysqli_fetch_array($result);

                //if such user not exist return message, else save user

                if (!empty($res)) {
                    $this->errorMessages['login'] = 'This login is in use. Try another.';
                } else {
                    $params['what'] = array('Login' => $this->login,
                        'Password' => password_hash($this->password, PASSWORD_BCRYPT, CRYPT_OPTIONS));
                    $this->id = $this->save($this->prepareQueryParams($params));
                }
            }
        } else {
            $this->errorMessages = array('login' => null,
                'password' => null,
                'repeatPassword' => null);
        }
    }
}