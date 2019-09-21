<?php

/**
 * Class holds all queries 
 *
 * @author Mariam
 */
class Database {

    /**
     * db connection 
     * @var PDO 
     */
    private $_db;

    public function __construct() {
        $this->_db = DatabaseConnection::getBdd();
    }

    /**
     * create new record 
     * @param Model $model
     * @param array $attributes
     * @return type
     */
    public function create(Model $model, array $attributes) {
        $parameters = $this->_extractFillableFields($model->fillable, $attributes);
        if ($model->createdAtField) {
            $parameters[$model->createdAtField] = date("Y-m-d H:i:s");
            $model->fillable[] = $model->createdAtField;
        }
        return $this->_insert($model, $parameters);
    }

    /**
     * update existing model
     * @param Model $model
     * @param int $id
     * @param array $attributes
     * @return boolean
     */
    public function update(Model $model, $id, array $attributes) {
        $parameters = $this->_extractFillableFields($model->fillable, $attributes);
        $sets = array();
        foreach (array_keys($parameters) as $key) {
            $sets[] = "$key=:$key";
        }
        $sql = "UPDATE " . $model->getTable()
                . " SET  " . implode(", ", $sets) //name=:name, author=:author..
                . " WHERE id=:id";

        return $this->_db->prepare($sql)
                        ->execute(array_merge($parameters, [":id" => $id]));
    }

    /**
     * retrieve model record from db
     * @param Model $model
     * @param int $id
     * @return array
     */
    public function find(Model $model, $id) {
        $sql = "SELECT * FROM " . $model->getTable() . " WHERE id = :id";
        $q = $this->_db->prepare($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $q->bindParam(':id', $id, PDO::PARAM_INT);
        $q->execute();
        return $q->fetch();
    }

    /**
     * check if certain field is uniqe
     * @param mixed $value
     * @param string $table
     * @param string $field
     * @param int $id
     * @return boolean
     */
    public function unique($value, $table, $field, $id = NULL) {
        $params = [":value" => $value];
        $sql = "SELECT * FROM $table WHERE $field = :value";
        if ($id) {
            $sql .= " AND id <> :id";
            $params[":id"] = $id;
        }
        $q = $this->_db->prepare($sql);
        $q->execute($params);
        return $q->fetch() ? false : true;
    }

    /**
     * delete model record
     * @param Model $model
     * @param int $id
     * @return boolean
     */
    public function delete(Model $model, $id) {
        $q = $this->_db->prepare("DELETE FROM " . $model->getTable() . " WHERE id=:id");
        $q->bindParam(":id", $id, PDO::PARAM_INT);
        return $q->execute();
    }

    /**
     * get all books
     * @param Model $model
     * @param array $select
     * @param array $searchCriteria
     * @return type
     */
    public function all(Model $model, $select = array(), $conditions = "", $params = array()) {
        $sql = $this->_allQuery($model, $select);
        if ($conditions) {
            $sql .= " AND " . $conditions;
        }
//        foreach ($searchCriteria as $field => $value) {
//            $sql .= " AND $field = :$field";
//        }
        $q = $this->_db->prepare($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $q->execute($params);
        return $q->fetchAll();
    }

    /**
     * select all records sql string
     * @param Model $model
     * @param array $select
     * @return string
     */
    private function _allQuery(Model $model, $select = null) {
        return "SELECT "
                . ($select ? (is_array($select) ? implode(", ", $select) : $select) : "*")
                . " FROM " . $model->getTable()
                . " WHERE 1";
    }

    /**
     * insert record to db
     * @param Model $model
     * @param array $parameters
     * @return boolean
     */
    private function _insert(Model $model, array $parameters) {
        $columnsValues = array_map(function($column) {
            return ":$column";
        }, array_keys($parameters));

        $sql = "INSERT INTO  " . $model->getTable()
                . " (" . implode(',', $model->fillable) . ") "
                . " VALUES "
                . " (" . implode(",", $columnsValues) . ")";
        return $this->_db->prepare($sql)->execute($parameters);
    }

    /**
     * extract only fillable fields
     * @param array $fillable
     * @param array $attributes
     * @return array
     */
    private function _extractFillableFields(array $fillable, array $attributes) {
        $parameters = array();
        foreach ($fillable as $field) {
            $parameters[$field] = isset($attributes[$field]) ? $attributes[$field] : NULL;
        }
        return $parameters;
    }

}
