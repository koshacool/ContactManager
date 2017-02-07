<?php
namespace Models;

use Core, Helper;

class ModelAddress extends ModelBase
{
    protected $contactId;
    protected $type;
    protected $address;

    /**
     * [prepareAttributes description]
     *
     *
     * @param  integer $contactId
     * @param  string $address
     * @param  integer $type address type(1 or 2)
     * @return void
     */
    public function prepareAttributes($contactId, $address, $type)
    {
        $param['what'] = array(
            'address_id' => 'address_id',
            'type' => 'type');
        $param['where'] = array(
            'contact_id' => $contactId,
            'type' => $type);
        $resultQuery = $this->find($this->prepareQueryParams($param));
        $this->createObject($resultQuery);
        $this->setAttribute('address', $address);
        $this->setAttribute('type', $type);
        $this->setAttribute('contactId', $contactId);
    }


    /**
     * [save description]
     * If no id, it means that is new data
     *   and you need to add it to DB and recieve id or
     *  update existing data
     *
     * @param  array $what What add to DB
     * @param  array $where params for save
     * @return void
     */
    protected function save($queryParams = null)
    {
        $what = array(
            'contact_id' => $this->contactId,
            'type' => $this->type,
            'address' => $this->address);

        if (empty($this->id)) {
            $this->id = $this->query->sqlQueryInsert(array(
                'arrayTableName' => array('tableName' => $this->tableName),
                'arrayWhat' => $what));
        } else {
            $this->query->sqlQueryUpdate(array(
                'arrayTableName' => array('tableName' => $this->tableName),
                'arrayWhat' => $what,
                'arrayWhere' => array('address_id' => $this->id)));
        }
    }
}