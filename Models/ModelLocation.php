<?php
namespace Models;
use Core, Helper;
class ModelLocation extends ModelBase
{
    protected $contactId;  
    protected $cityId;
    protected $stateId;
    protected $countryId;

    /**
     * [prepareAttributes description]
     *
     * 
     * @param  integer $contactId 
     * @param  integer $cityId    
     * @param  integer $stateId   
     * @param  integer $countryId 
     * @return void
     */
    public function prepareAttributes($contactId, $cityId, $stateId, $countryId) {
        $location = new ModelLocation();
        $param['what'] = array('selectAll' => '*');
        $param['where'] = array('contact_id' => $contactId);

        $resultQuery = $location->find($this->prepareQueryParams($param));

        $this->createObject($resultQuery);
        $this->setAttribute('contactId', $contactId);
        $this->setAttribute('cityId', $cityId);
        $this->setAttribute('stateId', $stateId);
        $this->setAttribute('countryId', $countryId);

    }

    /**
     * [save description]
     *  * If no id, it means that is new data 
     *   and you need to add it to DB and recieve id or 
     *  update existing data 
     * 
     * @param  array $what  What save
     * @param  array $where Params for save
     * @return void        
     */
    protected function save($queryParams = null)
    {
        if (empty($this->id)) {
            $this->id = $this->query->sqlQueryInsert(array(
                'arrayTableName' => array('tableName' => $this->tableName),
                'arrayWhat' =>array(
                    'contact_id' => $this->contactId,
                    'city_id'    => $this->cityId,
                    'state_id'   => $this->stateId,
                    'country_id' => $this->countryId)));
        } else {
            $this->query->sqlQueryUpdate(array(
                'arrayTableName' => array('tableName' => $this->tableName),
                'arrayWhat' => array(
                    'contact_id' => $this->contactId,
                    'city_id'    => $this->cityId,
                    'state_id'   => $this->stateId,
                    'country_id' => $this->countryId),
                'arrayWhere' => array('location_id' => $this->id)));
        }
    }

    /**
     * [createObject description]
     * Create model object and set attributes      
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
            $this->setAttribute('id', $result[strtolower($this->tableName) . '_id']);
            $this->setAttribute('contactId', $result['contact_id']);
            $this->setAttribute('cityId', $result['city_id']);
            $this->setAttribute('stateId', $result['state_id']);
            $this->setAttribute('countryId', $result['country_id']);
        }
    }
}