<?php

class Dispatcher {

    private $request;

    /**
     * parse url and call controller method
     */
    public function dispatch() {
        $this->request = new Request();
        Router::parse($this->request->url, $this->request);

        $controller = $this->loadController();

        if ($controller && method_exists($controller, $this->request->action)) {
            call_user_func_array([$controller, $this->request->action], $this->request->params);
        } else {
            $response = new Response();
            $response->error("Not found");
        }
    }

    /**
     * get required controller
     * @return Object of required controller
     */
    public function loadController() {
        $name = $this->request->controller;
        $file = 'controllers/' . $name . '.php';
        if (file_exists($file)) {
            require($file);
            $controller = new $name();
            return $controller;
        }
    }

}
