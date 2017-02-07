<?php
namespace Models;

use Helper, Core;

class ModelBase extends Core\Model
{
    protected $tableName;
    protected $id;
    public $errorMessages;
    protected $countError = false;
    protected $relationsData;
    protected $additionalData;

    function __construct()
    {
        parent::__construct();
        $this->tableName = strtolower(str_replace('Models\Model', '', get_class($this)));//Define DB table name   
    }

    /**
     * [validateData description]
     * Validation data by class Validation, which return number errors
     *
     * @param  array $data Associative array with data for validation
     * @param  string $userId User id
     * @param  string $contactId Contact id
     * @return void
     */
    protected function validateData($data, $userId = null, $contactId = null)
    {
        $validation = new Core\Validation();
        list($this->errorMessages, $this->countError) = $validation->validation($data, $userId, $contactId);

    }

    /**
     * [setAttributes description]
     *Set attributes
     *
     * @param arra $arrayData Associative array, key=attribute name & value=value
     */
    protected function setAttributes($arrayData)
    {
        foreach ($arrayData as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * [setAttribute description]
     * Set attribute
     *
     * @param strin ] $attributeName Class attribute name
     * @param type $value
     */
    public function setAttribute($attributeName, $value)
    {
        if (property_exists(get_class($this), $attributeName)) {
            $this->$attributeName = $value;
        }
    }

    /**
     * [getAttribute description]
     * Get class attribute value,
     * if not exist suct attribute - return null
     *
     * @param  string $attributeName Class attribute name
     * @return [type]                Class attribute value
     */
    public function getAttribute($attributeName)
    {
        if (property_exists(get_class($this), $attributeName)) {
            return $this->$attributeName;
        }
        return null;
    }

    /**
     * [setRelationsData description]
     * If not exist Attribute with such name,
     * save it to attribute 'relationData[]'
     *
     * @param array $data Associative array, key=attribute name & value=value
     * @return void
     */
    protected function setRelationsData(array $data)
    {
        foreach ($data as $key => $value) {
            if (!property_exists(get_class($this), $key)) {
                $this->relationsData[$key] = $value;
            }
        }
    }

    /**
     * [setRelationsData description]
     * If not exist Attribute with such name,
     * save it to attribute 'relationData[]' and set empty value
     *
     * @param array $data Associative array, key=attribute name & value=value
     * @return void
     */
    protected function setEmptyRelationsData(array $data)
    {
        foreach ($data as $key => $value) {
            if (!property_exists(get_class($this), $key)) {
                $this->relationsData[$key] = null;
            }
        }
        $this->relationsData['best_phone'] = 'cell';
    }

    /**
     * [getRelationData description]
     * If in array Attribute isset such value name
     * return it or return null
     *
     * @param  string $attributeName Class attribute name
     * @return [type]                Class attribute value
     */
    public function getRelationData($dataName)
    {
        if (isset($this->relationsData[$dataName])) {
            return $this->relationsData[$dataName];
        }
        return null;
    }

    /**
     * [save description]
     * Save Model object to DB
     *
     * @param  array $what What save
     * @param  array $where Params for save
     * @return void
     */
    protected function save($queryParams)
    {
        $this->query->sqlQueryUpdate($queryParams);
    }

    /**
     * [find description]
     * Find data in DB
     *
     * @param  array $what What get
     * @param  array $where params for search
     * @param  array $join Which tables join
     * @param  array $on Params for join
     * @return object $result Sql query result
     */
    protected function find($queryParams)
    {
        return $this->query->sqlQuerySelect($queryParams);
    }

    /**
     * [find description]
     * Find all data in DB
     *
     * @param  array $what What get
     * @param  array $where params for search
     * @param  array $join Which tables join
     * @param  array $on Params for join
     * @param  array $orderBy Sort getting data
     * @param  array $limit Number of getting data
     * @return object $result Sql query result
     */
    public function findAll($queryParams)
    {
        //Get all recoreds from DB
        return $this->query->sqlQuerySelect($queryParams);
    }

    /**
     * [delete description]
     * Remove model object from DB
     * @param  array $where Params for remove
     * @return object        Sql query result
     */
    protected function delete($queryParams)
    {
        return $this->query->sqlQueryDelete($queryParams);
    }

    /**
     * [createObject description]
     * Create model object and set attributes
     * and relations data
     *
     * @param  object $resultQuery Result sql query
     * @return void
     */
    protected function createObject($resultQuery)
    {
        if (!mysqli_num_rows($resultQuery)) {
            //Must be exeption
            $this->setAttribute('id', null);
        }
        while ($result = mysqli_fetch_array($resultQuery, MYSQLI_ASSOC)) {
            if (get_class($this) == 'Models\ModelContact' && $this->getAttribute('id') == 1) {
                $this->setEmptyRelationsData($result);
            } else {
                $this->setAttributes($result);
                $this->setRelationsData($result);
            }
            if (isset($result[strtolower($this->tableName) . '_id'])) {
                $this->setAttribute('id', $result[strtolower($this->tableName) . '_id']);//Set id received from db
            }

        }
    }

    /**
     * [createObjectsList description]
     * Create model object, set attributes
     * and relations data
     *
     * @param  object $resultQuery Sql query result
     * @return array              Array with created model objects
     */
    public function createObjectsList($resultQuery)
    {
        //$resultQuery = $this->findAll(array('selectAll' => '*'));
        if (!mysqli_num_rows($resultQuery)) {
            return null;
        }
        while ($result = mysqli_fetch_array($resultQuery, MYSQLI_ASSOC)) {
            $objectName = get_class($this);
            $obj = new $objectName;
            $obj->setRelationsData($result);
            $obj->setAttributes($result);
            $obj->setAttribute('id', $result[strtolower($this->tableName) . '_id']);
            $objArr[] = $obj;
        }
        return $objArr;
    }


    /**
     * [sortToAssociativeArray description]
     * Save array with objects to associative array, when keys is values of such table
     *
     * @param array $array Array with objects
     * @return array
     */
    protected function sortToAssociativeArray(array $array) {
        foreach ($array as $key => $obj) {
            $arr[$obj->getAttribute($this->tableName)] = $obj;
        }
        ksort($arr);
        return $arr;
    }

    /**
     * [setAdditionalData description]
     * Set additional data
     * @param string $nameValue Data name
     * @param [type] $value
     */
    protected function setAdditionalData($nameValue, $value)
    {
        $this->additionalData[$nameValue] = $value;
    }

    public function getAdditionalData($name)
    {
        if (isset($this->additionalData[$name])) {
            return $this->additionalData[$name];
        }
        return null;
    }

    /**
     * [prepareQueryParams description]
     *Prepare array with params for Sql Query
     *
     * @arr array Array wint params for conect data from one or more tables
     * $forGetingRelationsData boolean This value mean the query is for join another tables or not
     * @return array Array with params for sql query
     */
    public function prepareQueryParams($arrParams, $forGetingRelationsData = false)
    {
        $queryProperties = array(
            'arrayTableName' => array('tableName' => $this->tableName),
            'arrayWhat' => null,
            'arrayWhere' => null,
            'arrayJoin' => null,
            'arrayOn' => null,
            'arrayOrderBy' => null,
            'arrayLimit' => null);
        //When this values is true - this mean that is sql query with join another tables
        if ($forGetingRelationsData) {
            foreach ($arrParams as $table => $arrayParams) {
                foreach ($arrayParams as $paramName => $arrayValues) {
                    if (is_array($arrayValues)) {
                        if (is_null($queryProperties['array' . ucfirst($paramName)])) {
                            $queryProperties['array' . ucfirst($paramName)] = $arrayValues;
                        } else {
                            $queryProperties['array' . ucfirst($paramName)] = array_merge($queryProperties['array' . ucfirst($paramName)], $arrayValues);
                        }
                    } else {
                        $tempArr = explode(",", $arrayValues);
                        foreach ($tempArr as $key => $value) {
                            $value = trim($value);
                            if (is_null($queryProperties['array' . ucfirst($paramName)])) {
                                $queryProperties['array' . ucfirst($paramName)] = array($value => $value);
                            } else {
                                $queryProperties['array' . ucfirst($paramName)] = array_merge($queryProperties['array' . ucfirst($paramName)], array($value => $value));
                            }
                        }
                    }
                }
            }

        } else {
            foreach ($arrParams as $paramName => $value) {
                $queryProperties['array' . ucfirst($paramName)] = $value;
            }
        }
        return $queryProperties;
    }

}