<?php
namespace Models;

use Core, Helper;

class ModelPhone extends ModelBase
{
    protected $contactId;
    protected $typeId;
    protected $bestPhone = 0;
    protected $phone;

    /**
     * [prepareAttributes description]
     *
     *
     * @param  integer $contactId
     * @param  integer $type
     * @param  string $bestPhone
     * @param  integer $phone
     * @return void
     */
    public function prepareAttributes($contactId, $type, $bestPhone, $phone)
    {
        $phoneTypes = array(
            'home' => 1,
            'work' => 2,
            'cell' => 3);

        if ($bestPhone == $type) {
            $this->bestPhone = '1';
        } else {
            $this->bestPhone = '0';
        }

        $param['what'] = array('phone_id' => 'phone_id');
        $param['where'] = array(
            'contact_id' => $contactId,
            'type_id' => $phoneTypes[$type]);

        $resultQuery = $this->find($this->prepareQueryParams($param));

        $this->createObject($resultQuery);
        $this->setAttribute('contactId', $contactId);
        $this->setAttribute('typeId', $phoneTypes[$type]);
        $this->setAttribute('bestPhone', $this->bestPhone);
        $this->setAttribute('phone', $phone);


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
            'type_id' => $this->typeId,
            'phone' => $this->phone,
            'best_phone' => $this->bestPhone);

        if (empty($this->id)) {
            $this->query->sqlQueryInsert(array(
                'arrayTableName' => array('tableName' => $this->tableName),
                'arrayWhat' => $what));
        } else {
            $this->query->sqlQueryUpdate(array(
                'arrayTableName' => array('tableName' => $this->tableName),
                'arrayWhat' => $what,
                'arrayWhere' => array('phone_id' => $this->id)));
        }
    }
}
