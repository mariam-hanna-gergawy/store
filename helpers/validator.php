<?php

/**
 * check for required field
 * @param mixed $value
 * @return boolean true if valid else string represents error key
 */
function required($value) {
    return $value ? true : "empty_param";
}

/**
 * check date format
 * @param string $value
 * @param string $format
 * @return boolean true if valid else string represents error key
 */
function is_date($value, $format = 'Y-m-d') {
    if (!$value) {
        return true;
    }
    $d = DateTime::createFromFormat($format, $value);
    $valid = $d && $d->format($format) === $value;
    return $valid ? true : "invalid_date_format";
}

/**
 * validate unique
 * @param mixed $value
 * @param string $table
 * @param string $field
 * @param int $id
 * @return boolean true if valid else string represents error key
 */
function unique($value, $table, $field, $id = NULL) {
    $db = new Database();
    return $db->unique($value, $table, $field, $id) ? true : "not_unique";
}
