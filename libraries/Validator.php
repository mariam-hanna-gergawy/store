<?php

/**
 * Class to validate data from users
 *
 * @author Mariam
 */
class Validator {

    /**
     * array holds errors 
     * its key represents field points to array with errors
     * @var array 
     */
    protected $errors = array();
    private $_method;
    private $_params;

    /**
     * validate data with certain rules
     */
    public function validate($rules, $data) {
        foreach ($rules as $filed => $filedRules) {
            $this->_validateField($filed, isset($data[$filed]) ? $data[$filed] : NULL, $filedRules);
        }
        return $this;
    }

    /**
     * validate field rules
     * @param string $filed
     * @param mixed $value
     * @param array $rules
     */
    private function _validateField($filed, $value, $rules) {
        foreach ($rules as $rule) {
            $this->_validateFieldRule($filed, $value, $rule);
        }
    }

    /**
     * validate rule and if fail set error key in errors array
     * @param string $filed
     * @param mixed $value
     * @param string $rule //function name
     */
    private function _validateFieldRule($filed, $value, $rule) {
        $this->_getRuleParts($rule);

        $valid = call_user_func_array($this->_method, array_merge([$value], $this->_params));
        if ($valid !== true) {
            if (!isset($this->errors[$filed])) {
                $this->errors[$filed] = array();
            }
            $this->errors[$filed][] = $valid;
        }
    }

    private function _getRuleParts($rule) {
        $explodeRule = explode(':', trim($rule));
        $this->_method = $explodeRule[0];
        $this->_params = isset($explodeRule[1]) ? explode(',', $explodeRule[1]) : array();
    }

    /**
     * retrieve errors array
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * check if success validation
     * @return boolean
     */
    public function success() {
        return $this->errors ? false : true;
    }

}
