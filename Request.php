<?php

class Request {

    public $url;

    public function __construct() {
        $this->url = $_SERVER["REQUEST_URI"];
    }

    public function post($filter = null, $addEmpty = true, $field = null) {
        return $this->_params(INPUT_POST, $filter, $addEmpty, $field);
    }

    public function get($filter = null, $addEmpty = true, $field = null) {
        return $this->_params(INPUT_GET, $filter, $addEmpty, $field);
    }

    private function _params($type, $filter = null, $addEmpty = true, $field = null) {
        $data = $filter ? filter_input_array($type, $filter, $addEmpty) : filter_input_array($type);
        return $field ? (isset($data[$field]) ? $data[$field] : null) : $data;
    }

}
