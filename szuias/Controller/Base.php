<?php

namespace Controller;

class Base {

    static protected $app;
    static protected $request;

    static public function before() {}

    static public function after() {}

    static public function _get() {
        self::before();
        $return = call_user_func_array(array(get_called_class(), 'get'), func_get_args()); 
        self::after();
        return $return;
    }

    static public function _post() {
        self::before();
        $return = call_user_func_array(array(get_called_class(), 'post'), func_get_args()); 
        self::after();
        return $return;
    }

    static public function _put() {
        self::before();
        $return = call_user_func_array(array(get_called_class(), 'put'), func_get_args()); 
        self::after();
        return $return;
    }

    static public function _delete() {
        self::before();
        $return = call_user_func_array(array(get_called_class(), 'delete'), func_get_args()); 
        self::after();
        return $return;
    }

    static public function setApp($app) {
        self::$app = $app;
        self::$request = $app->request;
    }

    static protected function render($path, $args) {
        return self::$app->render($path, $args);
    }

    static public function redirect($url) {
        return self::$app->redirect($url);
    }

    static public function urlFor($name, $params = array()) {
        return self::$app->urlFor($name, $params);
    }

}