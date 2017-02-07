<?php
namespace Models;

use Core;

class ModelContact extends ModelBase
{
    protected $userId;
    protected $first;
    protected $last;
    protected $email;
    protected $birthday;
    protected $relationsData;
    protected $additionalData;

    /**
     * [save description]
     * If id=1 - it means that is new data
     *   and you need to add it to DB and save
     *   new id to attribute id
     *
     * @param  array $what What add to DB
     * @param  array $where params for save
     * @return void
     */
    protected function save($queryParams = null)
    {
        if (is_array($this->birthday)) {
            $this->birthday = $this->birthday['year'] . '-' . $this->birthday['month'] . '-' . $this->birthday['day'];
        }

        $param['what'] = array(
            'user_id' => $this->userId,
            'first' => $this->first,
            'last' => $this->last,
            'email' => $this->email,
            'birthday' => $this->birthday);
        $param['where'] = array('contact_id' => $this->id);
        if ($this->id == 1) {
            $this->id = $this->query->sqlQueryInsert(array(
                'arrayTableName' => array('tableName' => $this->tableName),
                'arrayWhat' => $param['what']));

        } else {
            parent::save($this->prepareQueryParams($param));
        }
    }

    /**
     * [getArrayProperties description]
     * Find attribute and if such exist
     * save it to array and then return it
     *
     * @param  string $propertyList String with attribute names, saparated coma
     * @return array Associative array with Attributes name and value
     */
    public function getArrayProperties($propertyList)
    {
        $arrayPropertyNames = explode(',', $propertyList);
        foreach ($arrayPropertyNames as $value) {
            $value = trim($value);
            if (property_exists(get_class($this), $value)) {
                $arrayProperties[$value] = $this->getAttribute($value);
            } else {
                $arrayProperties[$value] = $this->getRelationData($value);
            }
        }
        return $arrayProperties;
    }

    /**
     * [emptyErrorMessages description]
     * Save to attribute errorMessages array with
     * names taken from incoming data and set empty value
     *
     * @param  string $propertyList Array with attributes names
     * @return void
     */
    private function emptyErrorMessages($propertyList)
    {
        $arrayPropertyNames = explode(',', $propertyList);
        foreach ($arrayPropertyNames as $value) {
            $this->errorMessages[trim($value)] = null;
        }
    }

    /**
     * [setAttributes description]
     * Assepts associative array with attribute names and value.
     * If such attribute exist - set value
     *
     * @param array $arrayData Associative array with attributes names and value
     * @return void
     */
    protected function setAttributes($arrayData)
    {
        foreach ($arrayData as $key => $value) {
            if (property_exists(get_class($this), $key)) {
                if ($key == 'birthday') {
                    if (!is_array($value) && !empty($value)) {
                        $date = preg_split("/[,.:-]+/", $value);
                        $value = null;
                        $value['day'] = $date[2];
                        $value['month'] = $date[1];
                        $value['year'] = $date[0];
                    } elseif (!is_array($value) && empty($value)) {
                        $value['day'] = null;
                        $value['month'] = null;
                        $value['year'] = null;
                    }
                }
                $this->$key = $value;
            }
        }
    }

    /**
     * [setAttributes description]
     * Assepts associative array with attribute names and value.
     * If such attribute exist - set empty value
     *
     * @param array $arrayData Associative array with attributes names and value
     * @return void
     */
    protected function setEmptyAttributes($arrayData)
    {
        foreach ($arrayData as $key => $value) {
            if (property_exists(get_class($this), $key)) {
                $this->$key = null;
            }
        }
    }

    /**
     * [getContactWithRelationsData description]
     *Prepare and do query for get contact with all relations data
     *
     * @return object Sql query result
     */
    protected function getContactWithRelationsData()
    {
        $arrParams['contact'] = array(
            'what' => 'first, last, email, birthday',
            'where' => array(
                'contact.contact_id' => $this->getAttribute('id'),
                'contact.user_id' => $this->getAttribute('id') == '1' ? '12' : $this->getAttribute('userId'))
        );

        $arrParams['phone'] = array(
            'join' => array('phone' => 'phone'),
            'on' => array('phone.contact_id' => $this->getAttribute('id')),
            'where' => array('phone.best_phone' => '1'),
        );

        $arrParams['bestPhone'] = array(
            'what' => array('bp.type' => 'best_phone'),
            'join' => array('bp' => 'phone_type'),
            'on' => array('phone.type_id' => 'bp.type_id')
        );

        $arrParams['home'] = array(
            'what' => array('ph1.phone' => 'home'),
            'join' => array('ph1' => 'phone'),
            'on' => array('ph1.contact_id' => $this->getAttribute('id')),
            'where' => array('ph1.type_id' => '1')
        );

        $arrParams['work'] = array(
            'what' => array('ph2.phone' => 'work'),
            'join' => array('ph2' => 'phone'),
            'on' => array('ph2.contact_id' => $this->getAttribute('id')),
            'where' => array('ph2.type_id' => '2')
        );

        $arrParams['cell'] = array(
            'what' => array('ph3.phone' => 'cell'),
            'join' => array('ph3' => 'phone'),
            'on' => array('ph3.contact_id' => $this->getAttribute('id')),
            'where' => array('ph3.type_id' => '3')
        );

        $arrParams['address1'] = array(
            'what' => array('a1.address' => 'address1'),
            'join' => array('a1' => 'address'),
            'on' => array('a1.contact_id' => $this->getAttribute('id')),
            'where' => array('a1.type' => '1')
        );

        $arrParams['address2'] = array(
            'what' => array('a2.address' => 'address2'),
            'join' => array('a2' => 'address'),
            'on' => array('a2.contact_id' => $this->getAttribute('id')),
            'where' => array('a2.type' => '2')
        );

        $arrParams['location'] = array(
            'join' => array('l1' => 'location'),
            'on' => array('l1.contact_id' => $this->getAttribute('id'))
        );

        $arrParams['city'] = array(
            'what' => array(
                'city.city' => 'city',
                'city.zip' => 'zip'),
            'join' => array('city' => 'city'),
            'on' => array('l1.city_id' => 'city.city_id')
        );

        $arrParams['state'] = array(
            'what' => array('state.state' => 'state'),
            'join' => array('state' => 'state'),
            'on' => array('l1.state_id' => 'state.state_id')
        );

        $arrParams['country'] = array(
            'what' => array('country.country' => 'country'),
            'join' => array('country' => 'country'),
            'on' => array('l1.country_id' => 'country.country_id')
        );

        return $this->find($this->prepareQueryParams($arrParams, true));
    }

    /**
     *Get contacts list
     *
     * @param array $arrayValues Array with data
     * @return array
     */
    public function showlist(array $arrayValues)
    {
        $this->userId = $arrayValues['userId'];
        unset($arrayValues['userId']);

        //Get data for sort data in SQL query
        $sorting = new Core\Sort();
        $sortValues = $sorting->sortTable($arrayValues);

        //Count all available pages
        $pagination = new Core\Pagination();
        $sortValues['numberOfPages'] = $pagination->countPages($this->userId);

        //Get pages for display pages links
        $firstLastPages = $pagination->pagination($sortValues['currentPage'], $sortValues['numberOfPages']);
        $data = array_merge($firstLastPages, $sortValues);
        $this->setAdditionalData('values', $data);

        $arrParams['contact'] = array(
            'what' => array(
                'contact.contact_id' => 'contact_id',
                'contact.first' => 'first',
                'contact.last' => 'last',
                'contact.email' => 'email'),
            'where' => array('user_id' => $this->userId),
            'orderBy' => array(
                $data['mainSortColumn'] => $data['sortDirectionMainColumn'],
                $data['secondarySortColumn'] => $data['sortDirectionSecondaryColumn']),
            'limit' => array($data['offset'] => ROWS_ON_PAGE));

        $arrParams['phone'] = array(
            'what' => array('phone.phone' => 'phone'),
            'where' => array('phone.best_phone' => '1'),
            'join' => array('phone' => 'phone'),
            'on' => array('contact.contact_id' => 'phone.contact_id'));

        $result = $this->findAll($this->prepareQueryParams($arrParams, $getRelationData = true));

        $contacts = $this->createObjectsList($result);
        $this->setAdditionalData('contacts', $contacts);
    }

    /**
     *Remove contact from database
     *
     * @param integer $contactId Contact's id in database whose data get
     * @return void
     */
    public function removeContact(array $arrayValues)
    {
        $this->setAttribute('id', $arrayValues['contactId']);
        $this->setAttribute('userId', $arrayValues['userId']);

        $arrParams['where'] = array(
            'contact_id' => $this->id,
            'user_id' => $this->userId);
        $result = $this->delete($this->prepareQueryParams($arrParams));

        return $result;
    }

    /**
     *Get information about contact
     *
     * @param array $arrayValues Array with data
     * @return array
     */
    public function view()
    {
        $this->createObject($this->getContactWithRelationsData());

        $propertyList = ('first, last, email, home, 
           work, cell, address1, address2, 
           city, state, zip, country, birthday');
        $this->setAdditionalData('properties', $this->getArrayProperties($propertyList));
    }

    /**
     *Get data for add new or edit exist contact
     *
     * @param array $arrayValues Array with data
     * @return array
     */
    public function record(array $arrayValues)
    {
        //Save object from database select
        $city = new ModelCity();
        $param['what'] = array('selectAll' => '*');
        $arrCity = $city->createObjectsList($city->findAll($city->prepareQueryParams($param)));
        $arrCity = $city->sortToAssociativeArray($arrCity);
        $this->setAdditionalData('cities', $arrCity);

        $state = new ModelState();
        $param['what'] = array('selectAll' => '*');
        $arrState = $state->createObjectsList($state->findAll($state->prepareQueryParams($param)));
        $arrState = $state->sortToAssociativeArray($arrState);
        $this->setAdditionalData('states', $arrState);

        $country = new ModelCountry();
        $param['what'] = array('selectAll' => '*');
        $arrCountry = $country->createObjectsList($country->findAll($country->prepareQueryParams($param)));
        $this->setAdditionalData('countries', $arrCountry);

        $this->createObject($this->getContactWithRelationsData());

        $propertyList = ('first, last, email, home, 
           work, cell, address1, address2, 
           city, state, zip, country, birthday, best_phone');
        $this->emptyErrorMessages($propertyList);
        $data = $arrayValues['postData'];


        if (!empty($data)) {
            $data['birthday'] = array('day' => $data['day'],
                'month' => $data['month'],
                'year' => $data['year']);
            unset($data['day']);
            unset($data['month']);
            unset($data['year']);

            $this->setRelationsData($data);
            $this->setAttributes($data);

            //Check push select menu
            $this->checkSelectDataFromList($data);

            $this->setAdditionalData('properties', $this->getArrayProperties($propertyList));
            $this->validateData($this->getAdditionalData('properties'), $this->userId, $this->id);//Validation data and get message if not valid data

            //If data valid - save contact and his relation data
            if (!$this->countError) {
                $this->save();
                $this->saveRelationData();
                return true;
            }
        }
        $this->setAdditionalData('properties', $this->getArrayProperties($propertyList));
    }

    /**
     *Get emails for send it
     *
     * @param array $arrayValues Array with data
     * @return array
     */
    public function emails(array $arrayValues)
    {
        $this->errorMessages = null;
        $this->setAdditionalData('issetNewEmails', false);
        $this->setAdditionalData('arrayLastShownRowsOnPage', null);
        $this->setAdditionalData('arrayEmails', null);
        $this->setAdditionalData('emails', $arrayValues['emails']);

        if (!empty($arrayValues['postData'])) {

            $postData = $arrayValues['postData'];
//            unset($arrayValues['postData']);

            //If user push 'Select Email' button
            if (isset($postData['selectEmails'])) {
                $this->errorMessages = null;
                $this->setAdditionalData('emails', $postData['emails']);
                $this->setAdditionalData('select', true);

            //If user push 'Send', validation emails, save new emails and then redirect to main page
            } elseif (isset($postData['send'])) {
                $issetNewEmails = false;

                $validation = new Core\Validation();
                $this->setAdditionalData('emails', $validation->removeGapFromDataString($postData['emails'])); //remove whitespeces from after each email
                $this->validateData(array('emails' => explode(',', $this->getAdditionalData('emails'))), $this->userId, $this->id);//Validation data and get message if not valid data

                //If the validation is successful display new emails for save it
                if (!$this->countError) {
                    $this->setAdditionalData('send', true);
                }
            }
        }
    }

    /**
     *Get selected emails
     *
     * @param array $arrayValues Array with data
     * @return array
     */
    public function select(array $arrayValues)
    {
        $arrSelectedEmails = null;
        $emails = null;

        $this->userId = $arrayValues['userId'];
        unset($arrayValues['userId']);

        //Get data for sort data in SQL query
        $sorting = new Core\Sort();
        $sortValues = $sorting->sortTable($arrayValues);

        //Count all available pages
        $pagination = new Core\Pagination();
        $sortValues['numberOfPages'] = $pagination->countPages($this->userId);

        //Get pages for display pages links
        $firstLastPages = $pagination->pagination($sortValues['currentPage'], $sortValues['numberOfPages']);
        $sortData = array_merge($firstLastPages, $sortValues);

        $arrParams['contact'] = array(
            'what' => array(
                'contact.contact_id' => 'contact_id',
                'contact.first' => 'first',
                'contact.last' => 'last',
                'contact.email' => 'email'),
            'where' => array('user_id' => $this->userId),
            'orderBy' => array(
                $sortData['mainSortColumn'] => $sortData['sortDirectionMainColumn'],
                $sortData['secondarySortColumn'] => $sortData['sortDirectionSecondaryColumn']),
            'limit' => array($sortData['offset'] => ROWS_ON_PAGE));

        $arrParams['phone'] = array(
            'what' => array('phone.phone' => 'phone'),
            'where' => array('phone.best_phone' => '1'),
            'join' => array('phone' => 'phone'),
            'on' => array('contact.contact_id' => 'phone.contact_id'));

        $result = $this->findAll($this->prepareQueryParams($arrParams, $getRelationData = true));

        $contacts = $this->createObjectsList($result);
        $this->setAdditionalData('contacts', $contacts);

        //Save entered emails from page send Emails
        if (!empty($arrayValues['emailsFromCookie'])) {
            //convert input string with emails to array
            $arraySaveEmails = explode(',', $arrayValues['emailsFromCookie']);
            unset($arrayValues['emailsFromCookie']);

            //Remove whitespaces in values
            foreach ($arraySaveEmails as $key => $value) {
                $arraySaveEmails[$key] = trim($value);
            }
            $emails = null;

            //Get all emails from database
            $arrParams['what'] = array(
                'contact_id' => 'contact_id',
                'email' => 'email');
            $arrParams['where'] = array('user_id' => $this->userId);
            $allEmails = $this->findAll($this->prepareQueryParams($arrParams));

            //Save in array only new emails
            while ($row = mysqli_fetch_array($allEmails, MYSQLI_ASSOC)) {
                //Find selected emails
                if (($key = array_search($row['email'], $arraySaveEmails)) !== false) {
                    $arrSelectedEmails[$row['contact_id']] = $row['email'];
                    unset($arraySaveEmails[$key]);
                }
            }

            //Save emails from array to string value
            foreach ($arraySaveEmails as $key => $value) {
                if (empty($emails)) {
                    $emails = $value;
                } else {
                    $emails .= ',' . $value;
                }
            }
        } else {
            unset($arrayValues['emailsFromCookie']);
        }

        if (isset($arrayValues['post'])) {
            $postData = $arrayValues['post'];
            $arrSelectedEmails = $arrayValues['arrSelectedEmails']; ///Save selected emails to array
            //Save emails from page when user can send emails
            if (isset($postData['emails'])) {
                $emails = unserialize(base64_decode($postData['emails']));//Decode and unserialise data
            }

            //Save selected emails to array
            if (isset($postData['arrSelectedEmails'])) {
                $arrSelectedEmails = $postData['arrSelectedEmails']; //Decode and unserialise data
                $this->varDump->show($arrSelectedEmails);
            }

            //Save contacts that were displayed on last page
            if (isset($postData['arrayLastShownRowsOnPage'])) {
                $arrayLastShownRowsOnPage = unserialize(base64_decode($postData['arrayLastShownRowsOnPage']));//Decode and unserialise data
                list($arrSelectedEmails) = $this->saveSelect($postData, $arrSelectedEmails, $arrayLastShownRowsOnPage);//Check which emails from last page save to array
            }

            if (isset($postData['accept'])) {
                $this->setAdditionalData('accept', true);
            }
        }

        //Save data from SQL query result to two arrays
        foreach ($this->getAdditionalData('contacts') as $contact) {
            $arrayForTestSelect[$contact->getRelationData('contact_id')] = $contact->getAttribute('email');
        }

        //Check to display button 'selectAll' selected
        $selectAll = $this->checkPushSelectAll($arrSelectedEmails, $arrayForTestSelect);

        //Save emails to another array before serialise this array
        $arrForVerifySelect = $arrSelectedEmails;

        //Encode and serialise data
        $arrayLastShownRowsOnPage = base64_encode(serialize($arrayForTestSelect));
//        $arrSelectedEmails = base64_encode(serialize($arrSelectedEmails));
        $emails = base64_encode(serialize($emails));
        $data = compact('arrSelectedEmails', 'emails', 'arrayLastShownRowsOnPage', 'selectAll', 'arrForVerifySelect');
        $data = array_merge($sortData, $data);
        $this->setAdditionalData('values', $data);
    }

    /**
     *If email exist in database, remove it from array
     *
     * @param $connect Connect to DataBase
     * @param integer $user_id User's id, who add contact
     * @param array $arrayEmails Array with emails
     * @return array
     */
    public function findNewEmails(array $arrayEmails)
    {
        $arrParams['what'] = array('email' => 'email');
        $arrParams['where'] = array('user_id' => $this->userId);
        $allEmails = $this->findAll($this->prepareQueryParams($arrParams));

        //Save in array only new emails
        while ($row = mysqli_fetch_array($allEmails, MYSQLI_ASSOC)) {
            //Remove from array emails, such isset in DB
            $key = array_search($row['email'], $arrayEmails);
            if ($key !== false) {
                unset($arrayEmails[$key]);
            }
        }
        $this->setAdditionalData('newEmails', $arrayEmails);
    }

    /**
     *Add contact to database only with email
     *
     * @param $connect Connect to DataBase
     * @param array $arrayEmails Array with emails
     * @param integer $user_id User's id, who add contact
     * @return void
     */
    public function saveEmail($arrayEmails)
    {
        $this->id = 1;
        $this->createObject($this->getContactWithRelationsData());

        foreach ($arrayEmails as $key => $value) {
            $this->id = 1;
            $this->email = $value;
            $this->save();
            $this->saveRelationData();
        }
    }

    /**
     * [saveRelationData description]
     * Save all contact's relation data in DB
     *
     * @return [type] [description]
     */
    private function saveRelationData()
    {
        $city = new ModelCity();
        $city->prepareAttributes($this->getRelationData('city'), $this->getRelationData('zip'));
        $city->save();

        $country = new ModelCountry();
        $country->prepareAttributes($this->getRelationData('country'));
        $country->save();

        $state = new ModelState();
        $state->prepareAttributes($this->getRelationData('state'));
        $state->save();

        $location = new ModelLocation();
        $location->prepareAttributes($this->id, $city->getAttribute('id'), $state->getAttribute('id'), $country->getAttribute('id'));
        $location->save();

        $address = new ModelAddress();
        $address->prepareAttributes($this->id, $this->getRelationData('address1'), 1);
        $address->save();
        $address->prepareAttributes($this->id, $this->getRelationData('address2'), 2);
        $address->save();

        $phone = new ModelPhone();
        $phone->prepareAttributes($this->id, 'home', $this->getRelationData('best_phone'), $this->getRelationData('home'));
        $phone->save();
        $phone->prepareAttributes($this->id, 'work', $this->getRelationData('best_phone'), $this->getRelationData('work'));
        $phone->save();
        $phone->prepareAttributes($this->id, 'cell', $this->getRelationData('best_phone'), $this->getRelationData('cell'));
        $phone->save();
    }

    /**
     * Check to show checkbox 'Select All' selected or not selected on page EventContacts
     *
     * @param array $arrSelectSave Array with data selected on page earlier
     * @param array $arrDateShowOnPage Array with data displayed on the page
     * @return string
     */
    public function checkPushSelectAll($arrSelectSave, $arrDateShowOnPage)
    {
        $countRows = 0;//number displayed data on page
        $countSave = 0; //number saved data

        //count all shown data($countRows), count the saved data($countSave)
        foreach ($arrDateShowOnPage as $key => $value) {
            $countRows++;
            if (isset($arrSelectSave[$key])) {
                $countSave++;
            }
        }
        //check to select button 'SelectAll'
        if ($countRows == $countSave) {
            $selectAll = 'checked';
        } else {
            $selectAll = '';
        }
        return $selectAll;
    }

    /**
     * Check that user select data from drop list(city,state,country,zip)
     *
     * @param array $array Array with data getted by method POST or GET
     * @return void
     */
    public function checkSelectDataFromList($array)
    {
        //Data for check select menu
        $arrayForCheckSelect = array(
            'cities' => array('city'),
            'countries' => array('country'),
            'states' => array('state'),
            'zipSelect' => array('zip'));

        foreach ($arrayForCheckSelect as $selectName => $value) {
            if (isset($array[$selectName]) && ($array[$selectName] != 'false')) {
//                $this->countError = 1;
                list($tempValue1, $tempValue2) = explode(":", $array[$selectName]);

                if ($value[0] == 'zip') {
                    list($tempValue2, $tempValue1) = explode(":", $array[$selectName]);
                }
                $this->setRelationsData(array($value[0] => $tempValue2));
            }
        }

        //If select city - get all zips for city with such name    
        if ($array['cities'] != 'false') {
            $city = new ModelCity();
            $param['what'] = array('selectAll' => '*');
            $cities = $city->createObjectsList($city->findAll($city->prepareQueryParams($param)));
            foreach ($cities as $city) {
                list($cityZip, $cityName) = explode(':', $array['cities']);
                if ($city->getAttribute('city') == $cityName) {
                    $arrZip[$city->getAttribute('zip')] = $city->getAttribute('city');
                }
            }
            if (count($arrZip) > 1) {
                $this->setRelationsData(array('zip' => $arrZip));
            } else {
                $zip = array_keys($arrZip);
                $this->setRelationsData(array('zip' => $zip[0]));
            }
        }
    }

    /**
     * Save selected data on page to array
     *
     * @param array $arrayPostValues Array with data recieved from POST
     * @param array $arrSelectSave Array with data selected on page earlier
     * @param array $arrDateShowOnPage Array with data displayed on the page
     * @return array
     */
    public function saveSelect($arrayPostValues, $arrSelectSave, $arrDateShowOnPage)
    {
        $displayedEmails = 0;//number displayed data on page
        $savedEmails = 0;//number saved data
        $selectedEmails = 0;//number selected data on page

        //count all shown data($displayedEmails), count the saved data, count selected data($selectedEmails)
        foreach ($arrDateShowOnPage as $key => $value) {
            $displayedEmails++;
            if (isset($arrSelectSave[$key])) {
                $savedEmails++;
            }
            if (isset($arrayPostValues[$key])) {
                $selectedEmails++;
            }
        }

        //Check select all if exist checkbox selectAll
        if (isset($arrayPostValues['selectAll'])) {

            if ($displayedEmails == $selectedEmails) {//Save all emails which displayed in page
                foreach ($arrDateShowOnPage as $key => $value) {
                    $arrSelectSave[$key] = $value;
                }
            } else {//When didn't select all emails
                foreach ($arrDateShowOnPage as $key => $value) {
                    if ($displayedEmails == $savedEmails) {
                        if (!isset($arrayPostValues[$key])) {//Remove from saved emails not selected emails
                            if (isset($arrSelectSave[$key])) {
                                unset($arrSelectSave[$key]);
                            }
                        } else {//Save selected emails
                            $arrSelectSave[$key] = $value;
                        }
                    } else {
                        $arrSelectSave[$key] = $value;//save all shown rows
                    }
                }
            }

        } else {//Check select emails if not exist checkbox selectAll
            if ($displayedEmails == $selectedEmails && $displayedEmails == $savedEmails) {//Remove all shown emails
                foreach ($arrDateShowOnPage as $key => $value) {
                    if (isset($arrSelectSave[$key])) {
                        unset($arrSelectSave[$key]);
                    }
                }
            } elseif ($displayedEmails != $selectedEmails && $displayedEmails == $savedEmails) {//Remove all non-selected emails
                foreach ($arrDateShowOnPage as $key => $value) {
                    if (isset($arrSelect[$key])) {
                        $arrSelectSave[$key] = $value;
                    } else {
                        if (isset($arrSelectSave[$key])) {
                            unset($arrSelectSave[$key]);
                        }
                    }
                }
            } elseif ($displayedEmails == $selectedEmails && $displayedEmails != $savedEmails) {//save all shown emails
                foreach ($arrDateShowOnPage as $key => $value) {
                    $arrSelectSave[$key] = $value;
                }
            } else {//save only select rows
                foreach ($arrDateShowOnPage as $key => $value) {
                    if (isset($arrayPostValues[$key])) {
                        $arrSelectSave[$key] = $value;
                    } else {
                        if (isset($arrSelectSave[$key])) {
                            unset($arrSelectSave[$key]);
                        }
                    }

                }
            }
        }
        return array($arrSelectSave);
    }

}