<?php
namespace Core;
use Helper, Models;

class Validation extends Model
{
    private static $arrayProperties = array(
        //key is data name, value is pattern name
        'patternName' => array(
            'login' => 'login',
            'password' => 'password',
            'repeatPassword' => 'password',
            'home' => 'phone',
            'work' => 'phone',
            'cell' => 'phone',
            'best_phone' => 'best_phone',
            'first' => 'word',
            'last' => 'word',
            'address1' => 'address',
            'address2' => 'address',
            'city' => 'word',
            'state' => 'word',
            'country' => 'word',
            'currentPage' => 'pageNumber',
            'showPage' => 'pageNumber',
            'mainSortColumn' => 'sortColumnName',
            'sortDirectionMainColumn' => 'sortDirection',
            'sortDirectionSecondaryColumn' => 'sortDirection'),

        //key is pattern name, value is pattern
        'patterns' => array(
            'login' => '/^[0-9a-zA-Z]{3,12}+$/',
            'password' => '/^[a-zA-Z0-9_-]{6,18}$/',
            'phone' => '/^[0-9]{3,10}$/',
            'best_phone' => '/^(work)|(cell)|(home)$/',
            'word' => '/^[a-zA-Z]{1,20}$/',
            'address' => '/^([a-zA-Z]{1,20})\s(([a-zA-Z]{1,20}|[0-9]{1,5})|([a-zA-Z]{1,20}|[0-9]{1,5})\s[0-9]{1,5})$/',
            'pageNumber' => '/^[0-9]$/',
            'sortDirection' => '/^(ASC|DESC)$/',
            'sortColumnName' => '/^(last)|(first)$/'),

        //key is pattern name, value is message for pattern
        'messages' => array(
            'login' => 'Bad login. Use only latin chars and numbers.',
            'password' => 'Bad password. Length 6-18 symbols.',
            'phone' => 'Bad data. Use only numbers. Min length 3 max length 10',
            'best_phone' => '',
            'word' => 'Bad data. Use only latin chars',
            'address' => 'Bad data. Use only latin chars and numbers',
            'pageNumber' => 'falce',
            'sortColumnName' => 'falce',
            'sortDirection' => 'falce',),

        //key is data name, value is function name
        'exclusion' => array(
            'email' => 'validationEmailInForm',
            'emails' => 'validationEmails',
            'zip' => 'validationZip',
            'birthday' => 'validationBirthday'),

        //key is function name, value is parameters name
        'methodParam' => array(
            'validationEmailInForm' => 'email, userId, contactId',
            'validationEmails' => 'emails',
            'validationZip' => 'zip, city',
            'validationBirthday' => 'birthday'),

        //if empty data - return this message
        'empty' => 'Enter');

    /**
     *Check the entered data when user registration
     *
     * @param string $login Entered login
     * @param string $password Entered password
     * @param string $repeatPassword Entered repead password
     * @return array
     */
    public static function validation($arrayData, $userId = null, $contactId = null)
    {
        $countError = 0;
        $arrError = null;
        extract($arrayData);

        foreach ($arrayData as $key => $value) {
            //Validation by special method, if value's name is in array 'exclusion',
            // else search it in array patterns
            if (array_key_exists($key, self::$arrayProperties['exclusion'])) {
                $method = self::$arrayProperties['exclusion'][$key];


                //Remove whitespace beetwen values
                $string = self::removeGapFromDataString(self::$arrayProperties['methodParam'][$method]);

                //Put values name to array
                $paramNames = explode(',', $string);

                //Put values name to array
                foreach ($paramNames as $param) {
                    $parameters[$param] = $$param;
                }
                $arrError[$key] = self::$method($parameters);

                if (!empty($arrError[$key])) {
                    $countError++;
                }
                unset($parameters);
                //If value's name is in array 'patterns', validation it by pattern
            } elseif (array_key_exists($key, self::$arrayProperties['patternName'])) {

                //Check for empty value
                if (empty($value)) {
                    //get message for display it
                    $arrError[$key] = self::$arrayProperties['empty'] . ' ' . $key;

                    $countError++;
                } else {
                    //Validation value by pattern
                    if (preg_match(self::$arrayProperties['patterns'][self::$arrayProperties['patternName'][$key]], $value)) {
                        $arrError[$key] = '';
                    } else {
                        $arrError[$key] = self::$arrayProperties['messages'][self::$arrayProperties['patternName'][$key]];

                        $countError++;
                    }
                }
            }
        }

        return array($arrError, $countError);
    }

    /**
     *Validation entered email
     *
     * @param array $array array with email, userId & contactId
     * @return string
     */
    private static function validationEmailInForm($array)
    {
        $errorMessage = '';
        if (empty($array['email'])) {
            $errorMessage = 'Enter email';
        } else {
            if (!filter_var($array['email'], FILTER_VALIDATE_EMAIL)) {
                $errorMessage = 'Bad email';
            } else {
                //Check isset such email in DB for this user
                $params['what'] = array(
                    'email' => 'email',
                    'contact_id' => 'contact_id');
                $params['where'] = array(
                    'email' => $array['email'],
                    'contact_id' => $array['contactId']);
                $contact = new Models\ModelContact();
                $allEmails = $contact->findAll($contact->prepareQueryParams($params));
                while ($row = mysqli_fetch_array($allEmails, MYSQLI_ASSOC)) {
                    if ($row['contact_id'] != $array['contactId']) {
                        $errorMessage = 'This email is using already';
                    }
                }
            }
        }
        return $errorMessage;
    }

    /**
     *Validation entered email
     *
     * @param array $array array with emails
     * @return string
     */
    private static function validationEmails($array)
    {
        foreach ($array['emails'] as $value) {
            if (empty($value)) {
                $errorMessage = 'Enter email';
                break;
            } else {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $arrayBadEmails[] = $value;
                }
            }
        }

        if (isset($arrayBadEmails)) {
            $errorMessage = 'You enter bad email. After all emails except the last, must be a comma.';
        } elseif (!isset($errorMessage)) {
            $errorMessage = '';
        }
        
        return $errorMessage;
    }

    /**
     *Validation entered zip
     *
     * @param array $array array with zip and city
     * @return string
     */
    private static function validationZip($arrayValues)
    {
//        $varDump = new Helper\VarDump();
//        $varDump->show($arrayValues);
        if (empty($arrayValues['zip'])) {
            $errorMessage = 'Enter zip';
        } else {
            if (!is_array($arrayValues['zip']) && preg_match("/^[0-9]{1,10}$/", $arrayValues['zip'])) {
                $query = new SqlQuery();
                $resCity = $query->sqlQuerySelect(array('arrayTableName' => array('tableName' => 'city'),
                    'arrayWhat' => array('selectAll' => '*'),
                    'arrayWhere' => array('city.zip' => $arrayValues['zip'])));
                if (mysqli_num_rows($resCity) != 0) {
                    $row = (mysqli_fetch_array($resCity, MYSQLI_ASSOC));
                    $city = $row['city'];
                    if ($arrayValues['city'] != $city) {
                        $errorMessage = "This zip is using for " . $city;
                    } else {
                        $errorMessage = '';
                    }
                } else {
                    $errorMessage = '';
                }
            } else {
                $errorMessage = 'Bad zip. Use only numbers.';
            }
        }

        return $errorMessage;
    }

    /**
     *Validation birthday
     *
     * @param  integer $day Number day in month
     * @param  integer $month Number month in year
     * @param  integer $year Number year
     * @return string
     */
    private static function validationBirthday($data, $errorMessage = '')
    {
        $birthday = $data['birthday'];

        //Check for entered numeric and not empty date params
        foreach ($birthday as $key => $value) {
            if (empty($value) || !is_numeric($value)) {
                return 'Select date.';
            }
        }

        //Validation date & check contact age
        if (checkdate($birthday['month'], $birthday['day'], $birthday['year'])) {
            $enteredDate = $birthday['day'] . '.' . $birthday['month'] . '.' . $birthday['year'];//Save data to string
            $diffYears = self:: diffDateInYears($enteredDate);//Count diff
            //Check age
            if ($diffYears < 18) {
                $errorMessage = "Contact is younger than 18. It's bad.";
            }
            if ($diffYears > 80) {
                $errorMessage = "Contact is very old. It's bad";
            }
        } else {
            $errorMessage = 'Not valid date.';
        }
        return $errorMessage;
    }

    /**
     *Calculate the difference in years
     *
     * @param  date $date Date, for count diff from now
     * @return integer
     */
    private static function diffDateInYears($date)
    {
        //Get current date
        $currentDate = date("d.m.Y");
        $diffYears = floor((strtotime($currentDate) - strtotime($date)) / 60 / 60 / 24 / 365);
        return $diffYears;
    }

    /**
     *Remove gap in string
     *
     * @param string $string String with emails separated by a comma
     * @return string
     */
    public static function removeGapFromDataString($string)
    {
        $array  = explode(',', $string);//separates string to array
        $string = '';

        //save data from array to string and remove all gap
        foreach ($array as $key => $value) {
            $value = trim($value);//Strip whitespace (or other characters) from the beginning and end of a string
            if (empty($string)) {
                $string = $value;
            } else {
                $string .= ',' . $value;
            }
        }
        return $string;
    }

}