<?php

abstract class Model {

    /**
     * Model table
     * @var string 
     */
    protected $table;

    /**
     * array holds model's table fields which user can fill it with data
     * @var array 
     */
    public $fillable = array();

    /**
     * array holds errors
     * @var array 
     */
    public $errors = array();
    public $errorMessage;

    /**
     * creatin date field name if null don't set a creation date
     * @var string
     */
    public $createdAtField = "created_at"; //if null don't set it

    /**
     * validate model attributes
     * @param array $data
     * @param array $rules
     * @return boolean
     */

    public function validate($data, $rules) {
        $validator = new Validator();
        $valid = $validator->validate($rules, $data)->success();
        if (!$valid) {
            $this->errors = $validator->getErrors();
            $this->errorMessage = "invalid_parameters";
        }
        return $valid;
    }

    /**
     * to set validation rules to model 
     */
    abstract public function getRecordValidationRules();

    /**
     * insert new record in model's table
     * insert only allowed fields which will be if fillable variable
     * @param array $attributes
     * @return boolean
     */
    public function create(array $attributes) {
        $db = new Database();
        return $db->create($this, $attributes);
    }

    /**
     * retrieve model record from db
     * @param int $id
     * @return array
     */
    public function find($id) {
        $db = new Database();
        $record = $db->find($this, $id);
        if (!$record) {
            $this->errorMessage = "record_not_found";
        }
        return $record;
    }

    /**
     * update model certain record
     * @param int $id
     * @param array $attributes
     * @return boolean
     */
    public function update($id, array $attributes) {
        $db = new Database();
        return $db->update($this, $id, $attributes);
    }

    /**
     * delete certain record by id
     * @param int $id
     * @return boolean
     */
    public function delete($id) {
        $db = new Database();
        return $db->delete($this, $id);
    }

    /**
     * get model records which matched search criteria
     * @param array $select
     * @param array $searchCriteria
     * @return type
     */
//    public function all($select = null, $searchCriteria = array()) {
//        $db = new Database();
//        return $db->all($this, $select, $searchCriteria);
//    }

    /**
     * retrieve errors array
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * get model table
     * @return string
     */
    public function getTable() {
        return $this->table;
    }

}
