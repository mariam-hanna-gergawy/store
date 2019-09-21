<?php

/**
 * Get the url and explode it into 3 parts 
 * 1. COntroller
 * 2. Action if null set it to index (default action)
 * 3. Parameters
 * @author Mariam
 */
class Router {

    static public function parse($url, $request) {
        $explode_url = array_slice(explode('/', trim($url)), 2);
        $request->controller = ucfirst(self::_removeAllAfterString($explode_url[0])) . "Controller";
        $request->action = (isset($explode_url[1]) ? self::_removeAllAfterString($explode_url[1]) : "index") . "_" . strtolower($_SERVER['REQUEST_METHOD']);
        $request->params = array_slice($explode_url, 2);
    }

    static private function _removeAllAfterString($text, $string = "?") {
        return strpos($text, $string) !== false ? substr($text, 0, strpos($text, $string)) : $text;
    }

}
