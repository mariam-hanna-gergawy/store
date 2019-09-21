<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Book
 *
 * @author Mariam
 */
class Book extends Model {

    public function __construct() {
        $this->table = "books";
        $this->fillable = ['name', 'isbn', 'author', 'publish_date'];
        $this->searchable = ['name', 'isbn', 'author'];
    }

    /**
     * get record validation rules
     * @param int $id
     * @return array
     */
    public function getRecordValidationRules($id = NULL) {
        return array(
            'name' => ['required', 'unique:books,name' . ($id ? ',' . $id : '')],
            'isbn' => ['required', 'unique:books,isbn' . ($id ? ',' . $id : '')],
            'author' => ['required'],
            'publish_date' => ['is_date'],
        );
    }

    /**
     * get all books and search by name, isbn or author
     * @param type $searchCriteria
     * @return type
     */
    public function all(array $searchCriteria = array()) {
        $db = new Database();
        $conditions = array();
        $params = array();
        foreach ($searchCriteria as $field => $value) {
            if (in_array($field, $this->searchable)) {
                $conditions[] = " $field=:$field ";
                $params[$field] = $value;
            }
        }
        return $db->all($this, ["id", "name", "isbn", "author", "publish_date"], implode("AND ", $conditions), $params);
    }

}
