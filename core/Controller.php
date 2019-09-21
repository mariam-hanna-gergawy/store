<?php

class Controller {

    public $response;
    public $request;

    /**
     * filter request and set response object
     */
    public function __construct() {
        $this->secureData($_REQUEST);
        $this->response = new Response();
        $this->request = new Request();
    }

    private function secureInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    protected function secureData($data) {
        foreach ($data as &$value) {
            $value = $this->secureInput($value);
        }
    }

}
