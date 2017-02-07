<?php
namespace Models;
use Core, Helper;
class ModelState extends ModelBase
{
	protected $state;

    /**
     * [prepareAttributes description]
     *
     * @param  string $state          
     * @return void
     */
    public function prepareAttributes($state) {
        $stateObj = new ModelState();

        $param['what'] = array('state_id' => 'state_id');
        $param['where'] = array('state' => $state);
        $resultQuery = $stateObj->find($this->prepareQueryParams($param));

        $this->createObject($resultQuery);
        $this->setAttribute('country', $state);
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
        $what = array('state' => $this->state);

        if (empty($this->id)) {
            $this->id = $this->query->sqlQueryInsert(array(
                'arrayTableName' => array('tableName' => $this->tableName),
                'arrayWhat' => $what));
        }
    }
}