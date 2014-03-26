<?php

namespace Controller;

class Base {

    static protected $app;
    static protected $request;

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

}