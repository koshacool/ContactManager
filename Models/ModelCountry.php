<?php
namespace Models;
use Core, Helper;
class ModelCountry extends ModelBase
{
      
	protected $country;

    /**
     * [prepareAttributes description]
     *
     * @param  string $country          
     * @return void
     */
    public function prepareAttributes($country) {
        $countryObj  = new ModelCountry();
        $param['what']        = array('country_id' => 'country_id');
        $param['where']       = array('country' => $country);
        $resultQuery = $countryObj->find($this->prepareQueryParams($param));
        $this->createObject($resultQuery);
        $this->setAttribute('country', $country);
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
        $what = array('country' => $this->country);

        if (empty($this->id)) {
            $this->id = $this->query->sqlQueryInsert(array(
                'arrayTableName' => array('tableName' => $this->tableName),
                'arrayWhat' => $what));
        }
    }
     
}