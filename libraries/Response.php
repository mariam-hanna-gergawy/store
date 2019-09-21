<?php

/**
 * Description of Response
 *
 * @author Mariam
 */
class Response {

    private $_successField = 'success';
    private $_dataField = 'data';
    private $_messageFild = 'message';
    private $_errorsFild = 'errors';

    /**
     * success response
     * @param array $data
     */
    public function success($data = null) {
        $this->_response([
            $this->_successField => true,
            $this->_dataField => $data,
                ], 200);
    }

    /**
     * error response
     * @param string $message
     * @param array $errors
     */
    public function error($message, $errors = array()) {
        $this->_response([
            $this->_successField => false,
            $this->_messageFild => $message,
            $this->_errorsFild => $errors
                ], 400);
    }

    /**
     * 
     * @param array holds reponse data $array
     * @param int $httpCode
     */
    private function _response($array, $httpCode) {
        header('Content-Type: application/json');

        http_response_code($httpCode);
        exit(json_encode($array));
    }

}
