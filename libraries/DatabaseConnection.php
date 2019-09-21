<?php

/**
 * Singletone class to set DB Connection
 */
class DatabaseConnection {

    private static $bdd = null;

    private function __construct() {
        
    }

    public static function getBdd() {
        if (is_null(self::$bdd)) {
            self::$bdd = new PDO("mysql:host=localhost;dbname=store", 'root', '');
        }
        return self::$bdd;
    }

}
