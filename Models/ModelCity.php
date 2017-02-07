<?php
namespace Models;
use Core, Helper;

class ModelCity extends ModelBase
{
    protected $zip;
    protected $city;

    /**
     * [prepareAttributes description]
     *
     * @param  string $city 
     * @param  integer $zip      
     * @return void
     */
    public function prepareAttributes($city, $zip) {
        $cityObj = new ModelCity();
        $param['what'] = array('city_id' => 'city_id');
        $param['where'] = array('zip' => $zip);
        $resultQuery = $cityObj->find($this->prepareQueryParams($param));
        $this->createObject($resultQuery);
        $this->setAttribute('zip', $zip);
        $this->setAttribute('city', $city);
    }

    /**
     * [save description]
     * If no id, it means that is new data 
     *   and you need to add it to DB and recieve id  
     *  
     * @param  array $what    What add to DB
     * @param  array $where   params for save
     * @return void
     */
    protected function save($queryParams = null)
    {
        $what = array(
            'city' => $this->city,
            'zip' => $this->zip);

        if (empty($this->id)) {
            $this->id = $this->query->sqlQueryInsert(array(
                'arrayTableName' => array('tableName' => $this->tableName),
                'arrayWhat' => $what));
        }
    }

}