<?php

class Request {

    public $url;

    public function __construct() {
        $this->url = $_SERVER["REQUEST_URI"];
    }

    public function post($filter = null, $addEmpty = true) {
        return filter_input_array(INPUT_POST, $filter, $addEmpty);
    }

    public function get($filter = null, $addEmpty = true) {
        return filter_input_array(INPUT_GET, $filter, $addEmpty);
    }

}
